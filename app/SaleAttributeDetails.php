<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleAttributeDetails extends Model
{
    protected $guarded = [];

    public function saleDetails()
    {
        return $this->belongsTo(SaleDetail::class,'sale_detail_id');
    }
}
