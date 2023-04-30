<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalePayment extends Model
{
    protected $fillable = [
        'sale_id',
        'vendor_id',
        'warehouse_id',
        'pos_customer_id',
        'marketplace_user_id',
        'final_total',
        'payment_by',
        'check_no',
        'cheque_date',
        'card_name',
        'card_number',
        'account_no',
        'visible_name',
        'bank_name',
        'branch_name',
        'bank_account_name',
        'swift_code',
        'mobile_service_name',
        'payment_date',
        'transaction_date',
        'pay_amount',
        'due_amount',
        'give_back',
        'status',
        'comment',
        'image',
    ];
}
