<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PosCustomer extends Model
{
    protected $fillable = [
        'vendor_id',
        'warehouse_id',
        'name',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
        'updated_by',
    ];


    public function vendor()
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }

    public function posCustomerSales()
    {
        return $this->hasMany(Sale::class);
    }

    public function warehouseFromPosCustomer()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

}

