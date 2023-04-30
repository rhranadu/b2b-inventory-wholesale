<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{

    protected $fillable = [];
    protected $guarded = [];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id')->with('sale','product');
    }
    public function saleNegotiation()
    {
        return $this->belongsTo(SaleNegotiation::class, 'sale_negotiation_id');
    }
}
