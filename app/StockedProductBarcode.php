<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockedProductBarcode extends Model
{
    protected $guarded = [];

    public function productStockDetailFromBarcode()
    {
        return $this->belongsTo(StockDetail::class, 'stock_detail_id');
    }

    public function productFromBarcode()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function purchaseDetail()
    {
        return $this->belongsTo(PurchaseDetail::class, 'purchase_detail_id');
    }
    public function saleDetail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id');
    }
    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class, 'product_return_id');
    }
    public function productPoolStockDetail()
    {
        return $this->belongsTo(ProductPoolStockDetail::class, 'stock_detail_id', 'stock_detail_id');
    }


}
