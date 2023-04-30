<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPoolPurchaseDetail extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function productPool()
    {
        return $this->belongsTo(ProductPool::class, 'product_pool_id');
    }
    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class);
    }
}
