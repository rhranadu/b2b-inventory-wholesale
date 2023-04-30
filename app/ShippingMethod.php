<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingMethod extends Model
{
    use SoftDeletes;
    protected $table = 'shipping_methods';

    protected $fillable = [
        'name',
        'vendor_id',
        'charge',
        'delivery_time',
        'status',
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
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'id','vendor_id');
    }

}
