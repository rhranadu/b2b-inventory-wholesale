<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class VendorUser extends Authenticatable
{
    use Notifiable;
    protected $table = 'vendor_users';
    // protected $guard = 'vendor';
    protected $primaryKey = 'id';
    protected $guard = 'vendor'; // need for universal guard checking

    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {
            $model->created_by = auth()->user()->id;
        });
        static::updating(function($model)
        {
            $model->updated_by = auth()->id();
        });
    }

    // public function user_type()
    // {
    //     return $this->belongsTo(UserType::class);
    // }


    public function user_role()
    {
        return $this->belongsTo(UserRole::class, 'user_role_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

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
        'user_role_id',
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
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
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
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function userProducts()
    {
        return $this->hasMany(Product::class,'created_by');
    }

    public function vendor()
    {
        return $this->hasone(Vendor::class,'id', 'vendor_id');
    }


    public function country()
    {
        return $this->belongsTo(Country::class,'country_id');
    }

    public function stateOrDivision()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }



}
