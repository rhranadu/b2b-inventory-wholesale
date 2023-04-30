<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseAttributeDetails extends Model
{
    protected $guarded = [];

    public function purchaseDetails()
    {
        return $this->belongsTo(PurchaseDetail::class,'purchase_detail_id');
    }
}
