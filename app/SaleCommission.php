<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleCommission extends Model
{
    protected $table = 'sale_commissions';

    protected $guarded = [];

    public function commissionVendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

}
