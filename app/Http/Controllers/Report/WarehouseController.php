<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\ProductStockTransfer;
use App\StockDetail;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::active()->with('products')
            ->where('vendor_id', Auth::user()->vendor_id)->latest()->paginate(10);
        return view('reports.warehouses.index', compact('warehouses'));
    }

    //to do
    public function warehouseDetail(Warehouse $warehouse)
    {
        if ($warehouse->vendor_id === Auth::user()->vendor_id) {
            echo "SOMETHING BAD HAPPENED";
        } else {
            abort(403);
        }
    }
    //to do
    public function warehouseDetailProductFinish($id)
    {
        $stock_detail = StockDetail::findOrFail($id);
        if ($stock_detail->vendor_id === Auth::user()->vendor_id) {
            if ($stock_detail->status == 'report_to_admin') {
                $stock_detail->status = 'die';
                $stock_detail->save();
                return redirect()->back()->with('success', 'SuccessFully finished');
            } elseif (Auth::user()->user_type_id == 2 && $stock_detail->quantity == 0) {
                $stock_detail->status = 'die';
                $stock_detail->save();
                return redirect()->back()->with('success', 'SuccessFully finished');
            } else {
                return redirect()->back()->with('warning', 'Not allow to submit');
            }
        } else {
            abort(403);
        }
    }

    //to do
    public function warehouseSale(Warehouse $warehouse)
    {
        if ($warehouse->vendor_id === Auth::user()->vendor_id) {
            return view('reports.warehouses.sales', compact('warehouse'));
        }else{
            abort(404);
        }

    }

}
