<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseDetail extends Model
{
    protected $table = 'warehouse_details';

    protected $fillable = [
        'warehouse_id',
        'section_code',
        'section_name',
        'parent_section_id',
        'vendor_id',
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

    public function warehouse()
    {
        return $this->hasOne(Warehouse::class, 'id','warehouse_id');
    }

    public function products()
    {
        return $this->hasMany(StockDetail::class, 'warehouse_detail_id');
    }

    public function parent()
    {
        return $this->belongsTo(WarehouseDetail::class, 'parent_section_id','id');
    }

}
