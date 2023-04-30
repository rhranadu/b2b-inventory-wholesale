<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    public $timestamps = false;

    protected $table = 'password_resets';

    protected $fillable = [
        'email',
        'token',
        'user_id',
        'table_name',
        'created_at',
    ];
}
