<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleDetail extends Model
{
    protected $fillable = [
        'sale_id',
        'vendor_id',
        'product_id',
        'warehouse_id',
        'quantity',
        'return_quantity',
        'return_exchange_reference',
        'per_price',
        'status',
        'total',
        'average_purchase_price',
        'total_cumulative_purchase_price',
        'total_cumulative_sold_price',
        'total_cumulative_profit',
    ];
    protected $appends = ['product_attribute_id', 'product_attribute_map_id', 'product_attributes_pair', 'product_attributes_id_pair'];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }


    public function attribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'attribute_id');
    }

    public function attributeMap()
    {
        return $this->belongsTo(ProductAttributeMap::class, 'product_attribute_map_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    public function sales()
    {
        return $this->belongsTo(Sale::class, 'sale_id')->where('status','FP');
    }
    public function saleOrder()
    {
        return $this->hasOne(SaleOrder::class, 'sale_detail_id');
    }

    public function saleAttributeDetails()
    {
        return $this->hasMany(SaleAttributeDetails::class,'sale_detail_id','id');
    }
    public function soldBarcode()
    {
        return $this->hasMany(StockedProductBarcode::class,'sale_detail_id','id');
    }

    public function getProductAttributeMapIdAttribute() {
        $product_attribute_map_id = [];
        if ($this->saleAttributeDetails) {
            foreach ($this->saleAttributeDetails as $key => $value) {
                $product_attribute_map_id[] = $value->product_attribute_map_id;
            }
        }
        return implode(',', $product_attribute_map_id);
    }
    public function getProductAttributeIdAttribute() {
        $attribute_id = [];
        if ($this->saleAttributeDetails) {
            foreach ($this->saleAttributeDetails as $key => $value) {
                $attribute_id[] = $value->product_attribute_id;
            }
        }
        return implode(',', $attribute_id);
    }

    public function getProductAttributesPairAttribute() {
        $attributes = [];
        if ($this->saleAttributeDetails) {
            foreach ($this->saleAttributeDetails as $key => $value) {
                $attributes[] = $value->product_attribute_name . ': ' . $value->product_attribute_map_name;
            }
        }
        return implode(',<br>', $attributes);
    }
    public function getProductAttributesIdPairAttribute() {
        $attributes = [];
        if ($this->saleAttributeDetails) {
            foreach ($this->saleAttributeDetails as $key => $value) {
                $attributes[$value->product_attribute_id] = $value->product_attribute_map_id;
            }
        }
        return $attributes;
    }

    public function productPoolDetails()
    {
        return $this->hasOne(ProductPoolSaleDetail::class,'sale_detail_id', 'id');
    }
}
