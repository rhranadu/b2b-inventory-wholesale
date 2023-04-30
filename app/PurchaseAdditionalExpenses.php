<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseAdditionalExpenses extends Model
{
    protected $table = 'purchase_additional_expenses';

    protected $fillable = [
        'purchase_id',
        'vendor_id',
        'particular',
        'amount',
        'created_by',
        'updated_by',
    ];

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(), 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }
}
