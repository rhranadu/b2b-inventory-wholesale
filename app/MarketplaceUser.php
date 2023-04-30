<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class MarketplaceUser extends Authenticatable
{
    use Notifiable;
    protected $table = 'marketplace_users';
    protected $primaryKey = 'id';
    protected $guard = 'marketplace'; // need for universal guard checking

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'user_type_id',
        'image',
        'gender',
        'details',
        'status',
        'created_by',
        'image',
        'image_url',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function marketplaceUserAddreses()
    {
        return $this->hasMany(MarketplaceUserAddress::class, 'marketplace_user_id', 'id');
    }
}
