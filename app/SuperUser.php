<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperUser extends Authenticatable
{
    use Notifiable;
    protected $primaryKey = 'id';
    protected $guard = 'superadmin'; // need for universal guard checking

    public static function boot()
    {
        parent::boot();
        if (isset(Auth::user()->id)){
            static::creating(function($model)
            {
                $model->created_by = Auth::user()->id;
            });
            static::updating(function($model)
            {
                $model->updated_by = Auth::user()->id;
            });
        }
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
        'state_id',
        'city_id',
        'post_code',
        'user_role_id',
        'user_type_id',
        'gender',
        'date_of_birth',
        'image',
        'details',
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
