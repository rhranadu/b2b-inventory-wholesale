<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProductPoolSaleDetail extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function productPool()
    {
        return $this->belongsTo(ProductPool::class, 'product_pool_id');
    }
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id');
    }
}
