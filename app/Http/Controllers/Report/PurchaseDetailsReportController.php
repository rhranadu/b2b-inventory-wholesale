<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\ProductStock;
use App\Purchase;
use App\PurchaseDetail;
use App\Sale;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\Html\Builder;

class PurchaseDetailsReportController extends Controller
{

    public function index()
    {
        $title = 'Purchase Details Report';
        $page_detail = 'Purchase Details Report';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.purchases.purchase_details',compact('vendors','title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $purchase_details = PurchaseDetail::with('product')
            ->with(['purchases' => function($query){
                if(!empty(request()->supplier_id) ){
                    $query->where('supplier_id', request()->supplier_id );
                }
                $query->with('purchaseSupplier');

            }])
            ->with(['stockWarehouse' => function($query){
                $query->with(['productPoolDetails']);
            }]);
        if(!empty(Auth::user()->vendor_id) ){
            $purchase_details->where('vendor_id', Auth::user()->vendor_id);
        }
        if(!empty($request->vendor_id) ){
            $purchase_details->where('vendor_id', $request->vendor_id);
        }

        if(!empty($request->startDate) && !empty($request->endDate) ){
            $start_date = date('Y-m-d',strtotime($request->startDate));
            $end_date = date('Y-m-d',strtotime($request->endDate));
            $purchase_details->where('created_at', '>=', $start_date . ' 00:00:00');
            $purchase_details->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        $purchase_details = $purchase_details->latest()->get();
        foreach ($purchase_details as $purchase_detail){
            $purchase_detail->product_receive_total = $purchase_detail->stockWarehouse->sum('quantity');
            $purchase_detail->product_due_total = $purchase_detail->quantity - $purchase_detail->product_receive_total;
        }

        $p_d_data = array();
        foreach ($purchase_details as $key => $p_d){
            if (empty($p_d->purchases)){
                continue;
            }
            $p_d_data[$key]['invoice_no'] = $p_d->purchases->invoice_no;
            $p_d_data[$key]['supplier_name'] = $p_d->purchases->purchaseSupplier->name;
            $p_d_data[$key]['product_name'] = $p_d->product->name ?? 'N/A';
            $p_d_data[$key]['product_attributes'] = $p_d->product_attributes;
            $p_d_data[$key]['quantity'] = $p_d->quantity;
            $p_d_data[$key]['product_receive_total'] = $p_d->product_receive_total;
            $p_d_data[$key]['product_due_total'] = $p_d->product_due_total;
        }

        return DataTables::of($p_d_data)
            ->addIndexColumn()
            ->editColumn('invoice_no', function ($p_d_data) {
                return $p_d_data['invoice_no'];
            })
            ->editColumn('supplier_name', function ($p_d_data) {
                return $p_d_data['supplier_name'];
            })
            ->editColumn('product_name', function ($p_d_data) {
                return $p_d_data['product_name'];
            })
            ->editColumn('product_attributes', function ($p_d_data) {
                return $p_d_data['product_attributes'];
            })
            ->editColumn('quantity', function ($p_d_data) {
                return $p_d_data['quantity'];
            })
            ->editColumn('product_receive_total', function ($p_d_data) {
                return $p_d_data['product_receive_total'];
            })
            ->editColumn('product_due_total', function ($p_d_data) {
                return $p_d_data['product_due_total'];
            })
            ->rawColumns(['invoice_no','supplier_name','product_name','product_attributes','quantity','product_receive_total','product_due_total'])
            ->make(true);
    }

}
