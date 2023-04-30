<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Discount;
use App\ProductAttribute;
use App\ProductAttributeMap;
use PhpParser\Node\Expr\Cast;

class ProductPool extends Model
{
    protected $guarded = [];
    protected $casts = [
        'attribute_map_id' => 'array',
        'product_attribute' => 'array',
        'attribute_pair' => 'object',
    ];
    protected $appends = ['attribute_pair','attribute_pair_text','product_attribute','available_quantity'];

    public function getAttributePairAttribute() {
        $attributes = [];
        if ($this->attribute_map_id) {
            foreach ($this->attribute_map_id as $ami) {
                $attributes[ProductAttributeMap::find($ami)->attributeName->name] =  ProductAttributeMap::find($ami)->value ;
            }
        }
        return $attributes;
    }
    public function getProductAttributeAttribute() {
        $attributes = [];
        if ($this->attribute_map_id) {
            foreach ($this->attribute_map_id as $ami) {
                $attributes[] =  ProductAttributeMap::find($ami);
            }
        }
        return $attributes;
    }
    public function getAttributePairTextAttribute() {
        $attributes = [];
        if ($this->attribute_map_id) {
            foreach ($this->attribute_map_id as $ami) {
                $attributes[] = ProductAttributeMap::find($ami)->attributeName->name . ': ' . ProductAttributeMap::find($ami)->value;
            }
        }
        return implode(',<br>', $attributes);
    }


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(ProductPoolPurchaseDetail::class, 'product_pool_id', 'id');
    }
    public function saleDetails()
    {
        return $this->hasMany(ProductPoolSaleDetail::class, 'product_pool_id', 'id');
    }
    public function stockDetails()
    {
        return $this->hasMany(ProductPoolStockDetail::class, 'product_pool_id', 'id');
    }
    public function saleNegotiations()
    {
        return $this->hasMany(SaleNegotiation::class, 'product_pool_id', 'id');
    }
    public function productReturns()
    {
        return $this->hasMany(ProductPoolProductReturn::class, 'product_pool_id', 'id');
    }

    // return quantity is not added in stock but exchanged quantity is subtracted
    public function getAvailableQuantityAttribute() {
        return intval($this->stock_quantity) - intval($this->mp_order_submitted_quantity) - intval($this->pos_order_submitted_quantity) - intval($this->exchange_quantity);
    }
}
