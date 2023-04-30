<?php

namespace App;

use App\ProductPool;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Discount;

class Product extends Model
{
    protected $guarded = [];
    protected $appends = ['current_stock','available_quantity'];

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function productAttributeMap()
    {
        return $this->belongsTo(ProductAttributeMap::class, 'productmttributemap_id');
    }

    public function productBrand()
    {
        return $this->belongsTo(ProductBrand::class, 'product_brand_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function productTax()
    {
        return $this->belongsTo(Tax::class,'tax_id');
    }

    public function productManufacturer()
    {
        return $this->belongsTo(Manufacturer::class,'manufacturer_id');
    }

    public function productVendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function productCreatedUser()
    {
        return $this->belongsTo(auth()->user(),'created_by');
    }

    public function productUpdatedUser()
    {
        return $this->belongsTo(auth()->user(),'updated_by');
    }

    public function productPurchasesDetails()
    {
        return $this->hasMany(PurchaseDetail::class, 'product_id');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function sale_details()
    {
        return $this->hasMany(SaleDetail::class, 'product_id')->with('sales');
    }


    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


    public function productStockes()
    {
        return $this->hasMany(StockDetail::class);
    }
    public function stock()
    {
        return $this->hasMany(StockDetail::class, 'product_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Auth::user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Auth::user(), 'updated_by');
    }

    public function productBarcode()
    {
        return $this->hasMany(StockedProductBarcode::class, 'product_id');
    }

    public function product_pool() {
        return $this->hasMany(ProductPool::class, 'product_id','id');
    }
    public function getAvailableQuantityAttribute()
    {
        $pools = $this->product_pool()->get();
        $available_quantity = 0;
        if (!empty($pools)){
            foreach ($pools as $pool){
                $available_quantity += $pool->available_quantity;
            }
        }
        return $available_quantity;
    }

    public function getCurrentStockAttribute() {
        return $this->stock->sum('quantity');
    }

    public function childProductImage()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->where('order',1);
    }
    public function parentProduct()
    {
        return $this->belongsTo(ParentProduct::class, 'parent_product_id')->select(['id','name']);
    }

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }


    public function getPosDiscountPriceAttribute()
    {
        $discount = $this->__checkDiscountHierarchy('pos');
        if($discount){
            return !empty($discount->discount_percentage) ? ($this->max_price * (1 - ($discount->discount_percentage/100))) : ($this->max_price - $discount->discount_amount);
        }
        return $this->max_price;
    }
    public function getPosDiscountAmountAttribute()
    {
        $discount = $this->__checkDiscountHierarchy('pos');
        if($discount){
            return !empty($discount->discount_percentage) ? ($this->max_price * ($discount->discount_percentage/100)) : $discount->discount_amount;
        }
        return 0.00;
    }
    public function getMarketplaceDiscountPriceAttribute()
    {
        $discount = $this->__checkDiscountHierarchy('marketplace');
        if($discount){
            return !empty($discount->discount_percentage) ? ($this->max_price * (1 - ($discount->discount_percentage/100))) : ($this->max_price - $discount->discount_amount);
        }
        return $this->max_price;
    }
    public function getMarketplaceDiscountAmountAttribute()
    {
        $discount = $this->__checkDiscountHierarchy('marketplace');
        if($discount){
            return !empty($discount->discount_percentage) ? ($this->max_price * ($discount->discount_percentage/100)) : $discount->discount_amount;
        }
        return 0.00;
    }

    private function __checkDiscountHierarchy($discount_for = 'pos'){
        $now = date('Y-m-d H:i:s');
        $discountTypeModel = ['productVendor', 'productBrand', 'productCategory', 'products']; // this order must be preserved
        foreach($discountTypeModel as $model){
            if($model == 'products'){
                $discount = $this->discounts;
            }else{
                $discount = $this->$model->discounts;
            }
            $discount = $discount->where('status', 1)
                        ->where('discount_for', 'like', $discount_for)
                        ->where('start_at', '<=', $now)
                        ->filter(function ($value) use ($now) {
                            if(!intval($value->is_ongoing) > 0) {
                                return $value->end_at >= $now;
                            }
                            return $value;
                        })->first();
            if(!empty($discount)){
                break;
            }

        }
        return !empty($discount) ? $discount : false;
    }
}
