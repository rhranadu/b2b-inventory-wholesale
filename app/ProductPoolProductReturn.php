<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductPoolProductReturn extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function productPool()
    {
        return $this->belongsTo(ProductPool::class, 'product_pool_id');
    }
    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class, 'product_return_id');
    }
}
