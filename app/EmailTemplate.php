<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $table = 'email_templates';
    protected $fillable = [
        'super_user_id',
        'email_id',
        'body_content',
        'created_by',
        'updated_by',
    ];

    public function CreatedUser()
    {
        return $this->belongsTo(auth()->user(),'created_by');
    }

    public function UpdatedUser()
    {
        return $this->belongsTo(auth()->user(),'updated_by');
    }
}
