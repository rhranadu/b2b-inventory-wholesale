<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentProductImage extends Model
{

    protected $table = 'parent_product_images';
    protected $fillable = [
        'type',
        'product_id',
        'order',
        'original_path',
        'original_path_url',
        'x_600_path',
        'x_600_url',
        'x_300_path',
        'x_300_url',
        'x_100_path',
        'x_100_url',
        'x_50_path',
        'x_50_url',
        'created_by',
        'updated_by',
    ];
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(ParentProduct::class, 'product_id');
    }

}
