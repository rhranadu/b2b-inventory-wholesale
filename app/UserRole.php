<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRole extends Model
{
    use SoftDeletes;

    protected $table = 'user_roles';
    protected $fillable = ['name', 'description', 'user_type_id'];

    public function vendor_users()
    {
        return $this->hasMany('App\VendorUser');
    }
    public function super_users()
    {
        return $this->belongsToMany('App\SuperUser');
    }
    public function marketplace_users()
    {
        return $this->belongsToMany('App\MarketplaceUser');
    }
    public function user_type()
    {
        return $this->belongsTo(UserType::class, 'user_type_id','id')->select(['id','table_name']);
    }

}
