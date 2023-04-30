<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class SaleNegotiation extends Model
{
    protected $guarded = [];
    

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function vendorUser()
    {
        return $this->belongsTo(VendorUser::class, 'vendor_asked_by');
    }
    public function marketplaceUser()
    {
        return $this->belongsTo(MarketplaceUser::class, 'client_asked_by');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function productPool()
    {
        return $this->belongsTo(ProductPool::class, 'product_pool_id');
    }

    public function saleOrder()
    {
        return $this->hasOne(SaleOrder::class, 'sale_negotiation_id', 'id');
    }
}
