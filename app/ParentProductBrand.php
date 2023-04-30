<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentProductBrand extends Model
{
    use SoftDeletes;
    protected  $table = 'parent_product_brands';
    protected $fillable = [
        'name',
        'slug',
        'image',
        'image_url',
        'thumbnail_image',
        'thumbnail_image_url',
        'website',
        'status',
        'created_by',
        'updated_by',
    ];
}
