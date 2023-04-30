<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{

    protected $fillable = [
        'vendor_id',
        'pos_customer_id',
        'marketplace_user_id',
        'marketplace_user_address_id',
        'payment_method_id',
        'delivery_status',
        'payment_status',
        'shipping_method_id',
        'invoice_no',
        'type_of_sale',
        'items',
        'sub_total',
        'tax',
        'shipping_charge',
        'discount',
        'discount_percentage',
        'final_total',
        'commission_percentage',
        'superadmin_will_get',
        'vendor_will_get',
        'status',
        'user_warehouse_id',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'total_payment',
        'due_payment',
    ];


    public function posCustomer()
    {
        return $this->belongsTo(PosCustomer::class);
    }
    public function marketplaceUser()
    {
        return $this->belongsTo(MarketplaceUser::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class,'sale_id');
    }

    public function payment()
    {
        return $this->hasMany(SalePayment::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }

    public function saleWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'user_warehouse_id');
    }

    public function getTotalPaymentAttribute() {
        return $this->payment->sum('pay_amount');
    }
    public function getDuePaymentAttribute() {
        return $this->final_total - $this->total_payment;
    }

}
