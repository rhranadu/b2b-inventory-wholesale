<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function state()
    {
        return $this->belongsTo(State::class);
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
