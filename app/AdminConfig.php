<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminConfig extends Model
{
    use SoftDeletes;
    protected  $table = 'admin_configs';
    protected $fillable = ['name', 'value','created_by','updated_by'];
}
