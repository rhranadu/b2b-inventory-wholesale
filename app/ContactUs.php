<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactUs extends Model
{
    use SoftDeletes;
    protected $table = 'marketplace_contacts';
    protected $fillable = [
        'super_user_id',
        'phone',
        'email',
        'address',
        'social_links',
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
