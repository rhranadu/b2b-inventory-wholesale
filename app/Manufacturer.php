<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manufacturer extends Model
{

    protected $table = 'manufacturers';
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
        'parent_manufacturer_tbl_id',
        'is_approved',
        'status',
        'country_name',
        'vendor_id',
        'created_by',
        'updated_by',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function parentManufacturer()
    {
        return $this->belongsTo(ParentManufacturer::class,'parent_manufacturer_tbl_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function CreatedUser()
    {
        return $this->belongsTo(auth()->user(),'created_by');
    }

    public function UpdatedUser()
    {
        return $this->belongsTo(auth()->user(),'updated_by');
    }


}
