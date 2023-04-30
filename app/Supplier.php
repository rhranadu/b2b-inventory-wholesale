<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{

    protected $table = 'suppliers';
    protected $fillable = [
        'name',
        'email',
        'address',
        'mobile',
        'details',
        'website',
        'type',
        'image',
        'vendor_id',
        'status',
        'created_by',
        'updated_by',
    ];

    public function supplierPurchases()
    {
        return $this->hasMany(Purchase::class);
    }


    public function supplierPurchasesDetails()
    {
        return $this->hasManyThrough(PurchaseDetail::class,
            Purchase::class,'supplier_id','purchase_id','id', 'id');
    }


    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class,'account_owner_id');
    }
    public function supplierAccount()
    {
        return $this->hasOne(SupplierAccount::class,'supplier_id');
    }


  /*  public function supplierPaymentTranstion()
    {
        return $this->hasMany(PaymentTransaction::class,'supplier_id');
    }
*/

    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }


    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }

    public function paymentMethods()
    {
        return $this->hasMany(SupplierPaymentMethod::class);
    }

    public function ProductStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierPaymentTransaction::class);
    }


}
