<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductStock;
use App\Purchase;
use App\PurchaseDetail;
use App\Sale;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder;
use Illuminate\Support\Str;

class AlertQuantityReportController extends Controller
{

    public function index()
    {
        $title = 'Reorder Quantity Report';
        $page_detail = 'Reorder Quantity Report Details';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.alert_quantity.index',compact('vendors','title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cond = [];

        if (!empty(request()->brand_id)){
            $where = [['product_brand_id',request()->brand_id],['vendor_id', Auth::user()->vendor_id]];
        }else{
            $where = [['vendor_id', Auth::user()->vendor_id]];
        }
        if(!empty(Auth::user()->vendor_id)) {
            $cond = [['vendor_id', '=', Auth::user()->vendor_id ]];
        }
        if(!empty($request->product_id) ){
            $cond = [['id', '=', $request->product_id ]];
        }
        if(!empty($request->vendor_id) ){
            $cond = [['vendor_id', '=', $request->vendor_id ]];
        }
        if(!empty($request->startDate) && empty($request->endDate) ){
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00']];
        }
        if(!empty($request->endDate) && empty($request->startDate) ){
            $cond = [['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        if(!empty($request->endDate) && !empty($request->startDate) ){
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00'], ['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        $where = array_merge($where, $cond);
        $products = Product::where($where)->latest()->get();
        $alert_quantity_details = array();
        foreach ($products as $product){
            if ($product->alert_quantity > $product->available_quantity){
                $alert_quantity_details[] = $product;
            }
        }

        if($request->dashboard ){
            return DataTables::of($alert_quantity_details)
                ->editColumn('product.name', function ($alert_quantity_details) {
                    return Str::limit($alert_quantity_details->name, 100);
                })
                ->editColumn('quantity', function ($alert_quantity_details) {
                    return $alert_quantity_details->current_stock;
                })
                ->editColumn('product_receive_total', function ($alert_quantity_details) {
                    return $alert_quantity_details->available_quantity;
                })
                ->editColumn('product.alert_quantity', function ($alert_quantity_details) {
                    return $alert_quantity_details->alert_quantity;
                })
                ->addIndexColumn()
                ->setTotalRecords(10)
                ->make(true);
        }else {
            return DataTables::of($alert_quantity_details)
                ->editColumn('product.name', function ($alert_quantity_details) {
                    return Str::limit($alert_quantity_details->name, 100);
                })
                ->editColumn('quantity', function ($alert_quantity_details) {
                    return $alert_quantity_details->current_stock;
                })
                ->editColumn('product_receive_total', function ($alert_quantity_details) {
                    return $alert_quantity_details->available_quantity;
                })
                ->editColumn('product.alert_quantity', function ($alert_quantity_details) {
                    return $alert_quantity_details->alert_quantity;
                })
                ->addIndexColumn()
                ->make(true);
        }
    }


}
