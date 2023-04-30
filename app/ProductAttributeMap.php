<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttributeMap extends Model
{
    protected $fillable = [
        'vendor_id',
        'product_attribute_id',
        'product_id',
        'value',
        'attr_value',
        'created_by',
        'updated_by',
    ];

    public function attributeName()
    {
        return $this->belongsTo(ProductAttribute::class, 'product_attribute_id');
    }

    public function productAttributeMapPurchasesDetails()
    {
        return $this->hasMany(PurchaseAttributeDetails::class, 'product_attribute_map_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }
}
