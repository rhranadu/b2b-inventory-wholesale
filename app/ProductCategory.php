<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    protected $table = 'product_categories';
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'image_url',
        'thumbnail_image',
        'thumbnail_image_url',
        'parent_category_id',
        'status',
        'is_approved',
        'is_homepage',
        'position',
        'created_by',
        'updated_by'
    ];

    public function categoryProducts()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_category_id');
    }

    public function childes()
    {
        return $this->hasMany(ProductCategory::class, 'parent_category_id');
    }

    // recursive, loads all descendants
    public function childrenRecursive()
    {
        return $this->childes()->with('childrenRecursive')->where('id', '!=', $this->id);
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
