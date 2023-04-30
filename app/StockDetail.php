<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockDetail extends Model
{
    protected $fillable = [
        'product_stock_id',
        'vendor_id',
        'purchase_detail_id',
        'product_id',
        'warehouse_id',
        'warehouse_detail_id',
        'quantity',
        'alert_quantity',
        'price',
        'total_price',
        'status',
    ];
    protected $appends = ['attribute_id', 'product_attribute_map_id', 'product_attributes', 'product_attributes_id_pair'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function warehouseDetail()
    {
        return $this->belongsTo(WarehouseDetail::class, 'warehouse_detail_id');
    }
    public function productStock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }
    public function purchasesDetailsStatus()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_detail_id');
    }

    public function productBarcodesFromStockDetail()
    {
        return $this->hasMany(StockedProductBarcode::class,'stock_detail_id');
    }

    public function getProductAttributeMapIdAttribute() {
        $product_attribute_map_id = [];
        if ($this->purchasesDetailsStatus->purchaseAttributeDetails) {
            foreach ($this->purchasesDetailsStatus->purchaseAttributeDetails as $key => $value) {
                $product_attribute_map_id[] = $value->product_attribute_map_id;
            }
        }
        return implode(',', $product_attribute_map_id);
    }
    public function getAttributeIdAttribute() {
        $attribute_id = [];
        if ($this->purchasesDetailsStatus->purchaseAttributeDetails) {
            foreach ($this->purchasesDetailsStatus->purchaseAttributeDetails as $key => $value) {
                $attribute_id[] = $value->attribute_id;
            }
        }
        return implode(',', $attribute_id);
    }

    public function getProductAttributesAttribute() {
        $attributes = [];
        if ($this->purchasesDetailsStatus->purchaseAttributeDetails) {
            foreach ($this->purchasesDetailsStatus->purchaseAttributeDetails as $key => $value) {
                $attributes[] = $value->attribute_name . ': ' . $value->attribute_map_name;
            }
        }
        return implode(',<br>', $attributes);
    }
    public function getProductAttributesIdPairAttribute() {
        $attributes = [];
        if ($this->purchasesDetailsStatus->purchaseAttributeDetails) {
            foreach ($this->purchasesDetailsStatus->purchaseAttributeDetails as $key => $value) {
                $attributes[$value->attribute_id] = $value->product_attribute_map_id;
            }
        }
        return $attributes;
    }

    public function productPoolDetails()
    {
        return $this->hasOne(ProductPoolStockDetail::class,'stock_detail_id', 'id');
    }


}
