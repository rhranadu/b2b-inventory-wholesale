<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class VendorCommissionTransaction extends Model
{
    protected $table = 'vendor_commission_transactions';

    public function vendor()
    {
        return $this->hasone(Vendor::class,'id', 'vendor_id');
    }
}
