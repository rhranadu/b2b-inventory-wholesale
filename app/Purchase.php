<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    protected $guarded = [];

    public function supplierTransaction()
    {
        return $this->hasMany(SupplierPaymentTransaction::class);
    }
    public function purchaseDetail()
    {
        return $this->hasMany(PurchaseDetail::class)->with('stockWarehouse');
    }
    public function purchaseAdditionalExpense()
    {
        return $this->hasMany(PurchaseAdditionalExpenses::class);
    }
    public function purchaseDetailCount()
    {
        return $this->hasMany(PurchaseDetail::class)->count();
    }


    public function purchaseProductStock()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function purchaseVendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }


    public function purchaseSupplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }

    public function purchaseCreatedUser()
    {
        return $this->belongsTo(auth()->user(),'created_by');
    }

    public function purchaseUpdatedUser()
    {
        return $this->belongsTo(auth()->user(),'updated_by');
    }


   /* public function purchasePayment()
    {
        return $this->hasMany(PaymentTransaction::class,'purchase_id');
    }*/

    public static function getPurchasesFRStatus($purchase)
    {
        if ($purchase->status != 'draft' and $purchase->status != 'posted' and $purchase->status != 'FR')
        {
            $FR_status = PurchaseDetail::where('purchase_id', $purchase->id)->where('status', 'FR')->count();
            $NY_status = PurchaseDetail::where('purchase_id', $purchase->id)->where('status', 'NY')->count();
            $DC_status = PurchaseDetail::where('purchase_id', $purchase->id)->where('status', 'DC')->count();
            $All_status = PurchaseDetail::where('purchase_id', $purchase->id)->count();
            if ($FR_status == $All_status)
            {
                $purchase->status = 'FR';
            }elseif (($DC_status + $FR_status) == $All_status)
            {
                $purchase->status = 'FR';
            }else{
                if ($NY_status == $All_status)
                {
                    $purchase->status = 'NY';
                }else{
                    $purchase->status = 'PR';
                }
            }
            $purchase->save();
        }
        return $purchase->status;
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by')->select(['id','name']);
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by')->select(['id','name']);
    }






}
