<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    protected $fillable = [
        'vendor_id',
        'name',
        'address',
        'type',
        'status',
        'created_by',
        'updated_by',
    ];

  /*  public function warehouseStockDetails()
    {
        return $this->hasMany(StockDetail::class);
    }*/

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


    public function products()
    {
        return $this->hasMany(StockDetail::class, 'warehouse_id');
    }

    public function returnProducts()
    {
        return $this->hasMany(ProductReturn::class, 'warehouse_id');
    }

    public function warehouseSales()
    {
        return $this->hasMany(SaleDetail::class);
    }
    public function warehouseSaleOrders()
    {
        return $this->hasMany(SaleOrder::class);
    }
    public function warehouseUsers()
    {
        return $this->hasMany(auth()->user(), 'warehouse_id');
    }

    public function warehouseDetails()
    {
        return $this->hasMany(WarehouseDetail::class, 'warehouse_id','id')->select(['id','section_name']);
    }

}
