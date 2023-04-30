<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class SupplierPaymentTransaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'product_stock_id',
        'vendor_id',
        'supplier_id',
        'purchase_id',
        'purchase_invoice_no',
        'supplier_payment_method_id',
        'payment_by',
        'card_name',
        'card_number',
        'cheque_no',
        'cheque_date',
        'debit',
        'credit',
        'balance',
        'transaction_no',
        'transaction_date',
        'payment_date',
        'particulars',
        'note',
        'image',
        'created_by',
        'updated_by',
    ];

    public static function getSuppliersTotalAmount($getProduct_stock_id_arr)
    {
        $total = 0;
        foreach ($getProduct_stock_id_arr as $id)
        {
            $get_total  = StockDetail::where(['vendor_id' => Auth::user()->vendor_id, 'product_stock_id' => $id])->get()->sum('total_price');
            $total += $get_total;
        }
        return $total;
    }

    public function purchaseSupplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id');
    }
    public function purchase()
    {
        return $this->belongsTo(Purchase::class,'purchase_id');
    }

}
