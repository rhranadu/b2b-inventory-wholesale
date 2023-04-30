<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(auth()->user(),'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(auth()->user(), 'updated_by');
    }


}
