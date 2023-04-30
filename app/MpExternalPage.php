<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MpExternalPage extends Model
{
	protected $connection = 'mysql';
    use SoftDeletes;
    protected $table = 'mp_external_pages';
    protected $fillable = [
        'title',
        'slug',
        'descriptions',
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
}
