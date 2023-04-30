<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class MarketplaceUserAddress extends Model
{

    protected $table = 'marketplace_user_addresses';

    public function marketplaceUser()
    {
        return $this->belongsTo(MarketplaceUserAddress::class, 'marketplace_user_id');
    }
}
