<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceContracts extends Model
{
    use SoftDeletes;
    protected $table = 'marketplace_service_contracts';
    protected $fillable = [
        'super_user_id',
        'title',
        'slug',
        'position',
        'icon',
        'details',
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
