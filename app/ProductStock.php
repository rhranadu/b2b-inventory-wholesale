<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }


    public function productStockDetails()
    {
        return $this->hasMany(StockDetail::class, 'product_stock_id');
    }


    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }


    public static function stockSingleProductPurchasePrice($product_id, $purchases_id, $attribute_id, $product_attribute_map_id)
    {
        $price = PurchaseDetail::where(['product_id' => $product_id, 'purchase_id' => $purchases_id, 'attribute_id' => $attribute_id, 'product_attribute_map_id' => $product_attribute_map_id])->first();
        return $price;
    }

    public function purchaseSupplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

//    public function attribute_maps() {
//        return $this->hasMany('App\Models\AttributeMap','product_id', 'id')
//            ->with('attributes')
//            ->select(['id','product_id', 'product_attribute_id', 'value']);
//    }
}
