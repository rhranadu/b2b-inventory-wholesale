<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPoolStockDetail extends Model
{
    protected $guarded = [];
    protected $appends = ['available_quantity','available_warehouse_quantity'];
    public $timestamps = false;
    public function productPool()
    {
        return $this->belongsTo(ProductPool::class, 'product_pool_id');
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function warehouseDetail()
    {
        return $this->belongsTo(WarehouseDetail::class, 'warehouse_detail_id');
    }
    public function stockDetail()
    {
        return $this->belongsTo(StockDetail::class, 'stock_detail_id');
    }

    // return quantity is not added in stock but exchanged quantity is subtracted
    public function getAvailableQuantityAttribute() {
        return intval($this->stock_quantity) - intval($this->stock_transfer_quantity) - intval($this->mp_order_confirmed_quantity ) - intval($this->pos_order_submitted_quantity) - intval($this->exchange_quantity);
    }

    public function getAvailableWarehouseQuantityAttribute() {
        $data = $this->where('warehouse_id', $this->warehouse_id)->where('product_pool_id', $this->product_pool_id)->get();
        $qty = 0;
        foreach($data as $dt) {
            $qty = $qty + $dt->available_quantity;
        }
        return $qty;
    }
    public function stockBarcodes()
    {
        return $this->hasMany(StockedProductBarcode::class,'stock_detail_id', 'stock_detail_id');
    }
    public function productStockTransfers()
    {
        return $this->hasMany(ProductStockTransfer::class,'stock_detail_id', 'stock_detail_id');
    }
}
