<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseDetail extends Model
{

    protected $guarded = [];
    protected $fillable = [
        'purchase_id',
        'vendor_id',
        'product_id',
        'quantity',
        'price',
        'status',
        'created_at',
        'updated_at',
    ];
    protected $appends = ['product_attributes'];
    public function purchases()
    {
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function productWarehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function purchaseAttributeDetails()
    {
        return $this->hasMany(PurchaseAttributeDetails::class,'purchase_detail_id','id')->select(['id','attribute_id','attribute_name','product_attribute_map_id','attribute_map_name']);
    }

    public function barCodes()
    {
        return $this->hasMany(StockedProductBarcode::class,'purchase_detail_id');
    }

    public function stockWarehouse()
    {
        return $this->hasMany(StockDetail::class, 'purchase_detail_id','id');
    }

    public function attributeDetail()
    {
        return $this->hasMany(PurchaseAttributeDetails::class, 'purchase_detail_id','id');
    }
    public function productPoolDetails()
    {
        return $this->hasOne(ProductPoolPurchaseDetail::class,'purchase_detail_id', 'id');
    }
    public function getProductAttributesAttribute() {
        $attributes = [];
        if ($this->purchaseAttributeDetails) {
            foreach ($this->purchaseAttributeDetails as $key => $value) {
                $attributes[] = $value->attribute_name . ': ' . $value->attribute_map_name;
            }
        }
        return implode(',<br>', $attributes);
    }


}
