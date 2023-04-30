<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentManufacturer extends Model
{
    use SoftDeletes;
    protected $table = 'parent_manufacturers';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'website',
        'description',
        'image',
        'image_url',
        'thumbnail_image',
        'thumbnail_image_url',
        'status',
        'country_name',
        'created_by',
        'updated_by',
    ];
}
