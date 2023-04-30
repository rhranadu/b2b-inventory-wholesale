<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $guarded = [];


    public function users()
    {
        return $this->belongsToMany(auth()->user());
    }

    public function states()
    {
        return $this->hasMany(State::class);
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
