<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $guard = 'web'; // need for universal guard checking

    // public function userType()
    // {
    //     return $this->belongsTo(UserType::class);
    // }


    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'users_roles', 'user_id','user_role_id');
    // }

    // public function warehouse()
    // {
    //     return $this->belongsTo(Warehouse::class, 'warehouse_id');
    // }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'country_id',
        'user_type_id',
        'vendor_id',
        'state_id',
        'city_id',
        'post_code',
        'gender',
        'date_of_birth',
        'image',
        'details',
        'warehouse_id',
        'warehouse_type_name',
        'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];


    // public function userProducts()
    // {
    //     return $this->hasMany(Product::class,'created_by');
    // }

    // public function vendor()
    // {
    //     return $this->belongsTo(Vendor::class,'vendor_id');
    // }


    // public function country()
    // {
    //     return $this->belongsTo(Country::class,'country_id');
    // }

    // public function stateOrDivision()
    // {
    //     return $this->belongsTo(State::class, 'state_id');
    // }

    // public function city()
    // {
    //     return $this->belongsTo(City::class, 'city_id');
    // }


}
