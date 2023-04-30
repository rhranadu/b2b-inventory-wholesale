<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromotionalAdvertisement extends Model
{
    use SoftDeletes;
    protected $table = 'promotional_ad_settings';
    protected $fillable = [
        'name',
        'super_user_id',
        'slug',
        'position',
        'link',
        'image',
        'image_url',
        'thumbnail_image',
        'thumbnail_image_url',
        'status',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }
}
