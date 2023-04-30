<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReturn extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function returnedProductBarcode()
    {
        return $this->belongsTo(StockedProductBarcode::class, 'returned_stocked_product_barcode_id');
    }
    public function exchangedProductBarcode()
    {
        return $this->belongsTo(StockedProductBarcode::class, 'exchanged_stocked_product_barcode_id');
    }
    public function productPoolDetails()
    {
        return $this->hasOne(ProductPoolProductReturn::class,'product_return_id', 'id');
    }
}
