<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ParentProduct extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function productBrand()
    {
        return $this->belongsTo(ParentProductBrand::class, 'product_brand_id');
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }


    public function productManufacturer()
    {
        return $this->belongsTo(ParentManufacturer::class,'manufacturer_id');
    }

    public function productImages()
    {
        return $this->hasMany(ParentProductImage::class, 'product_id');

    }public function product_images()
    {
        return $this->hasMany(ParentProductImage::class, 'product_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'parent_product_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Auth::user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Auth::user(), 'updated_by');
    }

}
