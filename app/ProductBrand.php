<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBrand extends Model
{
    protected  $table = 'product_brands';
    protected $fillable = [
        'name',
        'slug',
        'image',
        'image_url',
        'thumbnail_image',
        'thumbnail_image_url',
        'website',
        'vendor_id',
        'status',
        'parent_product_brand_tbl_id',
        'is_approved',
        'created_by',
        'updated_by',
    ];

    public function brandProducts()
    {
        return $this->hasMany(Product::class,'product_brand_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
    public function parentBrand()
    {
        return $this->belongsTo(ParentProductBrand::class,'parent_product_brand_tbl_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

}
