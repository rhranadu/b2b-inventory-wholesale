<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SupplierAccount extends Model
{

    use SoftDeletes;
    protected $table = 'supplier_accounts';
    protected $fillable = [
        'supplier_id',
        'vendor_id',
        'balance',
        'status',
        'created_by',
        'updated_by',
    ];
}
