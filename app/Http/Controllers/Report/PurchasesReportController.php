<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Purchase;
use App\PurchaseDetail;
use App\Sale;
use App\Vendor;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
class PurchasesReportController extends Controller
{

    public function index()
    {
        $title = 'Product Purchases Report';
        $page_detail = 'Product Purchases Report Details';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.purchases.invoice_report',compact('vendors','title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $purchases = Purchase::with('purchaseSupplier','purchaseDetail','purchaseAdditionalExpense');

        if(!empty(Auth::user()->vendor_id) ){
            $purchases->where('vendor_id', Auth::user()->vendor_id);
        }
        if(!empty($request->vendor_id) ){
            $purchases->where('vendor_id', $request->vendor_id);
        }
        if(!empty($request->supplier_id) ){
            $purchases->where('supplier_id', $request->supplier_id );
        }
        if(!empty($request->startDate) && !empty($request->endDate) ){
            $start_date = date('Y-m-d',strtotime($request->startDate));
            $end_date = date('Y-m-d',strtotime($request->endDate));
            $purchases->where('created_at', '>=', $start_date . ' 00:00:00');
            $purchases->where('created_at', '<=', $end_date . ' 23:59:59');
        }
        $purchases = $purchases->latest()->get();

        foreach ($purchases as $purchase){
            $purchase->purchaseAdditionalExpense = $purchase->purchaseAdditionalExpense->sum('amount');
            $purchase->purchaseDetailCount = count($purchase->purchaseDetail);
        }

        if($request->dashboard ){
            return DataTables::of($purchases)
                ->addIndexColumn()
                ->editColumn('status', function ($purchase) {
                    if ($purchase->status == 'draft') return '<span class="badge badge-danger" >Draft</span>';
                    elseif ($purchase->status == 'posted') return '<span class="badge badge-primary">Posted</span>';
                    elseif ($purchase->status == 'FR') return '<span class="badge badge-success" >Full Received</span>';
                    elseif ($purchase->status == 'NY') return '<span class="badge badge-danger" >Not Yet</span>';
                    return ' <span class="badge badge-warning" >Partial Received</span>';
                })
                ->setTotalRecords(10)
                ->rawColumns(['status'])
                ->make(true);
        }else {
            return DataTables::of($purchases)
                ->addIndexColumn()
                ->editColumn('status', function ($purchase) {
                    if ($purchase->status == 'draft') return '<span class="badge badge-danger" >Draft</span>';
                    elseif ($purchase->status == 'posted') return '<span class="badge badge-primary">Posted</span>';
                    elseif ($purchase->status == 'FR') return '<span class="badge badge-success" >Full Received</span>';
                    elseif ($purchase->status == 'NY') return '<span class="badge badge-danger" >Not Yet</span>';
                    return ' <span class="badge badge-warning" >Partial Received</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }

    }

    public function purchaseDetail(Purchase $purchase)
    {
        ini_set('max_execution_time', 180);

        if (Auth::user()->vendor_id == $purchase->vendor_id)
        {
            return view('reports.purchases.in_details', compact('purchase'));
        }else{
            abort(403);
        }
    }

    public function supplierPurchaseReport(Request $request)
    {
        if (!$request->ajax())
        {
            $title = 'Supplier Wise Purchase';
            $page_detail = 'Supplier Wise Purchase';
            $vendors = Vendor::select('id','name')
                ->where([
                    ['status', 1],
                ])
                ->get();
            return view('reports.purchases.supplier_purchase',compact('vendors','title', 'page_detail'));
        }

        $purchases = Purchase::with('purchaseSupplier','purchaseDetail','purchaseAdditionalExpense');

        if(!empty(Auth::user()->vendor_id) ){
            $purchases->where('vendor_id', Auth::user()->vendor_id);
        }
        if(!empty($request->vendor_id) ){
            $purchases->where('vendor_id', $request->vendor_id);
        }
        if(!empty($request->supplier_id) ){
            $purchases->where('supplier_id', $request->supplier_id );
        }
        if(!empty($request->startDate) && !empty($request->endDate) ){
            $start_date = date('Y-m-d',strtotime($request->startDate));
            $end_date = date('Y-m-d',strtotime($request->endDate));
            $purchases->where('created_at', '>=', $start_date . ' 00:00:00');
            $purchases->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        $purchases = $purchases->latest()->get();

        foreach ($purchases as $purchase){
            $purchase->purchaseAdditionalExpense = $purchase->purchaseAdditionalExpense->sum('amount');
            $purchase->purchaseDetailCount = count($purchase->purchaseDetail);
        }

        if($request->dashboard ){
            return DataTables::of($purchases)
                ->addIndexColumn()
                ->editColumn('status', function ($purchase) {
                    if ($purchase->status == 'draft') return '<span class="badge badge-danger" >Draft</span>';
                    elseif ($purchase->status == 'posted') return '<span class="badge badge-primary">Posted</span>';
                    elseif ($purchase->status == 'FR') return '<span class="badge badge-success" >Full Received</span>';
                    elseif ($purchase->status == 'NY') return '<span class="badge badge-danger" >Not Yet</span>';
                    return ' <span class="badge badge-warning" >Partial Received</span>';
                })
                ->setTotalRecords(10)
                ->rawColumns(['status'])
                ->make(true);
        }else {
            return DataTables::of($purchases)
                ->addIndexColumn()
                ->editColumn('status', function ($purchase) {
                    if ($purchase->status == 'draft') return '<span class="badge badge-danger" >Draft</span>';
                    elseif ($purchase->status == 'posted') return '<span class="badge badge-primary">Posted</span>';
                    elseif ($purchase->status == 'FR') return '<span class="badge badge-success" >Full Received</span>';
                    elseif ($purchase->status == 'NY') return '<span class="badge badge-danger" >Not Yet</span>';
                    return ' <span class="badge badge-warning" >Partial Received</span>';
                })
                ->rawColumns(['status'])
                ->make(true);
        }
    }

    public function supplierPurchaseDetailReport(Request $request)
    {
        if (!$request->ajax())
        {
            $title = 'Supplier Wise Purchase Details';
            $page_detail = 'Supplier Wise Purchase Details';
            $vendors = Vendor::select('id','name')
                ->where([
                    ['status', 1],
                ])
                ->get();
            return view('reports.purchases.supplier_purchase_details',compact('vendors','title', 'page_detail'));
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

    public function brandWiseTotalPurchases(Request $request)
    {
        if (!$request->ajax())
        {
            $title        = 'Brand Wise Purchase';
            $page_detail  = 'List of Brand Wise Purchase Details';
            return view('reports.purchases.brand_wise_purchases', compact('title', 'page_detail'));
        }
        $purchase_details = PurchaseDetail::with(['product' => function($query){
            if(!empty(request()->brand_id)){
                $query->where('product_brand_id', request()->brand_id);
            }
                $query->with('productBrand');
            }])
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
        foreach ($purchase_details as $key => $p_d) {
            if (!empty($p_d->product)){
                    if (empty($p_d->purchases)) {
                        continue;
                    }
                $p_d_data[$key]['invoice_no'] = $p_d->purchases->invoice_no;
                $p_d_data[$key]['supplier_name'] = $p_d->purchases->purchaseSupplier->name;
                $p_d_data[$key]['brand_name'] = $p_d->product->productBrand->name;
                $p_d_data[$key]['product_name'] = $p_d->product->name;
                $p_d_data[$key]['product_attributes'] = $p_d->product_attributes;
                $p_d_data[$key]['quantity'] = $p_d->quantity;
                $p_d_data[$key]['product_receive_total'] = $p_d->product_receive_total;
                $p_d_data[$key]['product_due_total'] = $p_d->product_due_total;
            }
        }

        return DataTables::of($p_d_data)
            ->addIndexColumn()
            ->editColumn('invoice_no', function ($p_d_data) {
                return $p_d_data['invoice_no'];
            })
            ->editColumn('supplier_name', function ($p_d_data) {
                return $p_d_data['supplier_name'];
            })
            ->editColumn('brand_name', function ($p_d_data) {
                return $p_d_data['brand_name'];
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
            ->rawColumns(['invoice_no','supplier_name','brand_name','product_name','product_attributes','quantity','product_receive_total','product_due_total'])
            ->make(true);
    }

}
