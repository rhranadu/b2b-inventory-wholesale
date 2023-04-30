<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPaymentMethod extends Model
{
    use SoftDeletes;
    protected $table = 'supplier_payment_methods';
    protected $fillable = ['supplier_id', 'vendor_id', 'payment_type','visible_name','bank_name','branch_name','card_name','card_number',
        'bank_account_name','swift_code','account_no','mobile_service_name','status','created_by', 'updated_by'];

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }


}
