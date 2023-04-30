<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductAttribute extends Model
{
    protected $fillable = [
        'name',
        'vendor_id',
        'status',
        'created_by',
        'updated_by',
    ];

  /*  public function attributeProducts()
    {
        return $this->hasMany(Product::class, 'product_attribute_id');
    }*/

    public function attributeMaps()
    {
        return $this->hasMany(ProductAttributeMap::class, 'product_attribute_id');
    }

    public function productAttributePurchasesDetails()
    {
        return $this->hasMany(PurchaseAttributeDetails::class,'attribute_id');
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

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }


}
