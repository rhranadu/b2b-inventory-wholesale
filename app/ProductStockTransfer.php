<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStockTransfer extends Model
{

    protected $table = 'product_stock_transfers';

    protected $fillable = [
        'vendor_id',
        'stock_detail_id',
        'from_warehouse_id',
        'to_warehouse_id',
        'product_id',
        'delivery_quantity',
        'receive_quantity',
        'memo_no',
        'price',
        'total',
        'status',
        'created_by',
        'updated_by',
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function fromWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'from_warehouse_id');
    }
    public function toWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'to_warehouse_id');
    }
    
    public function transferProductStockDetail()
    {
        return $this->belongsTo(StockDetail::class, '   ');
    }
    public function productPoolStockDetail()
    {
        return $this->belongsTo(ProductPoolStockDetail::class, 'stock_detail_id', 'stock_detail_id');
    }
}
