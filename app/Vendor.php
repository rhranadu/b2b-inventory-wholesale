<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AdminConfig;
class Vendor extends Model
{
    use SoftDeletes;
    protected $table = 'vendors';

    protected $guarded = [];
    protected $appends = ['active_sale_commission'];
    public function vendorProducts()
    {
        return $this->hasMany(Product::class,'vendor_id');
    }
    public function vendorCategories()
    {
        return $this->hasMany(ProductCategory::class,'vendor_id');
    }
    public function vendorPurchases()
    {
        return $this->hasMany(Purchase::class);
    }
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function discounts()
    {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function vendorCommission()
    {
        return $this->hasMany(SaleCommission::class,'vendor_id');
    }

    public function getActiveSaleCommissionAttribute() {
        $commissions = $this->vendorCommission->where('status', 1)->first();
        if(!empty($commissions)) {
            return $commissions->commission_percentage;
        }
        return AdminConfig::where('name','global_sale_commission_percentage')->first()->value;
    }

}
