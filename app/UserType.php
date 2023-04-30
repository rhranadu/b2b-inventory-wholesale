<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class UserType extends Model
{
    protected $table = 'user_types';
    protected  $guarded = [];


    // public function users()
    // {
    //     return $this->hasMany(Auth::user());
    // }

}
