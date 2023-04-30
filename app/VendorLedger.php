<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorLedger extends Model
{

    protected $table = 'vendor_ledgers';
    protected $guarded = [];

}
