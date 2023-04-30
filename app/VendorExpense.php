<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorExpense extends Model
{
    protected $table = 'vendor_expenses';
    protected $fillable = [
        'vendor_id',
        'particulars',
        'expense_date',
        'total_amount',
        'pay_amount',
        'warehouse_id',
        'reason',
        'details',
        'status',
        'created_by',
        'updated_by',
    ];

    public function expenseWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }
}
