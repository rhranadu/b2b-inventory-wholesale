<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\MarketplaceUser;
use App\PosCustomer;
use App\Product;
use App\Sale;
use App\SaleDetail;
use App\Vendor;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
class SaleReportController extends Controller
{

    public function index()
    {
        $title = 'Product Sales Report';
        $page_detail = 'Product Sales Report Details';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.sales.index',compact('vendors','title', 'page_detail'));
    }


    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cond = [];
        $where = [['vendor_id', Auth::user()->vendor_id]];
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

        $sales = Sale::with('saleWarehouse','posCustomer','payment')->where($where)->latest()->get();

        foreach ($sales as $sale){
            if (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'PP'){
                $sale->status = 'Partial Paid';
            }elseif (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'FP'){
                $sale->status = 'Full Paid';
            }else{
                $sale->status = 'Full Due';
            }
            if ($sale->saleWarehouse){
                $sale->saleWarehouseName = $sale->saleWarehouse->name;
            }else{
                $sale->saleWarehouseName = 'N/A';
            }
            if (isset($sale->payment->last()->due_amount)){
            $sale->due_amount = $sale->payment->last()->due_amount;
            }
            if ($sale->discount_percentage > 0){
                $sale->discount =  $sale->sub_total / 100 * $sale->discount_percentage;;
            }else{
                $sale->discount =  $sale->discount;
            }
            $sale->pay_amount = $sale->final_total - $sale->due_amount;
            $sale->final_total = number_format($sale->final_total, 2,'.','');
            $now = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at)->format('Y-m-d');
            $sale->created_date = $now;
        }

        if($request->dashboard ){
            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('type_of_sale', function ($sales) {
                    if ($sales->type_of_sale == 1) return 'POS';
                    else return 'Marketplace';
                })
                ->editColumn('status', function ($sales) {
                    if ($sales->status == 'Partial Paid') return '<span class="badge badge-warning" >Partial Paid</span>';
                    elseif ($sales->status == 'Full Paid') return '<span class="badge badge-success">Full Paid</span>';
                    return ' <span class="badge badge-danger" >Full Due</span>';
                })
                ->setTotalRecords(10)
                ->rawColumns(['status','type_of_sale'])
                ->make(true);
        } else {
            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('type_of_sale', function ($sales) {
                    if ($sales->type_of_sale == 1) return 'POS';
                    else return 'Marketplace';
                })
                ->editColumn('status', function ($sales) {
                    if ($sales->status == 'Partial Paid') return '<span class="badge badge-warning" >Partial Paid</span>';
                    elseif ($sales->status == 'Full Paid') return '<span class="badge badge-success">Full Paid</span>';
                    return '<span class="badge badge-danger" >Full Due</span>';
                })
                ->rawColumns(['status','type_of_sale'])
                ->make(true);
        }

    }
    public function saleComissionDetail(Request $request)
    {
        if (!$request->ajax())
        {
            $title = 'Sale Commission Report';
            $page_detail = 'Sale Commission Report';
            // $vendors = Vendor::select('id','name')
            //     ->where([
            //         ['status', 1],
            //     ])
            //     ->get();
            return view('reports.sales.commission_detail',compact('title', 'page_detail'));
        }
        $query = Sale::with('saleWarehouse','posCustomer','payment');
        if(!empty($request->vendor_id) ){
            $query->where('vendor_id', $request->vendor_id);
        } elseif(!empty(auth()->user()->vendor_id)){
            $query->where('vendor_id', auth()->user()->vendor_id);
        }

        if(!empty(request()->start_date) && empty(request()->end_date) ){
            $query->where('created_at', '>=', request()->start_date . ' 00:00:00');
        }
        if(!empty(request()->end_date) && empty(request()->start_date) ){
            $query->where('created_at', '<=', request()->end_date . ' 23:59:59');
        }
        if(!empty(request()->end_date) && !empty(request()->start_date) ){
            $query->where('created_at', '>=', request()->start_date . ' 00:00:00');
            $query->where('created_at', '<=', request()->end_date . ' 23:59:59');
        }
        if(!empty(request()->invoice_no)){
            $query->where('invoice_no', trim(request()->invoice_no));
        }

        $query = $query->latest()->get();

        // foreach ($query as $sale){
        //     if (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'PP'){
        //         $sale->status = 'Partial Paid';
        //     }elseif (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'FP'){
        //         $sale->status = 'Full Paid';
        //     }else{
        //         $sale->status = 'Full Due';
        //     }
        //     if ($sale->saleWarehouse){
        //         $sale->saleWarehouseName = $sale->saleWarehouse->name;
        //     }else{
        //         $sale->saleWarehouseName = 'N/A';
        //     }
        //     if (isset($sale->payment->last()->due_amount)){
        //     $sale->due_amount = $sale->payment->last()->due_amount;
        //     }
        //     $sale->pay_amount = $sale->final_total - $sale->due_amount;
        //     $sale->final_total = number_format($sale->final_total, 2,'.','');
        //     $now = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at)->format('Y-m-d');
        //     $sale->created_date = $now;
        // }


        return DataTables::of($query)
            ->addIndexColumn()
            ->make(true);
    }

    public function orderShipment(Request $request)
    {
        if (!$request->ajax())
        {
            $title = 'Order Shipment Report';
            $page_detail = 'Order Shipment Report Details';
            $vendors = Vendor::select('id','name')
                ->where([
                    ['status', 1],
                ])
                ->get();
            return view('reports.sales.order_shipment',compact('vendors','title', 'page_detail'));
        }
        $cond = [];
        $where = [['vendor_id', Auth::user()->vendor_id]];
        if(!empty($request->vendor_id) ){
            $cond = [['vendor_id', '=', $request->vendor_id ]];
        }
        if(!empty($request->invoice_no) ){
            $cond = [['invoice_no', '=', $request->invoice_no ]];
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

        $sales = Sale::with('saleWarehouse','posCustomer','payment')->where($where)->latest()->get();

        foreach ($sales as $sale){
            if (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'PP'){
                $sale->status = 'Partial Paid';
            }elseif (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'FP'){
                $sale->status = 'Full Paid';
            }else{
                $sale->status = 'Full Due';
            }
            if ($sale->saleWarehouse){
                $sale->saleWarehouseName = $sale->saleWarehouse->name;
            }else{
                $sale->saleWarehouseName = 'N/A';
            }
            if (isset($sale->payment->last()->due_amount)){
            $sale->due_amount = $sale->payment->last()->due_amount;
            }
            $sale->pay_amount = $sale->final_total - $sale->due_amount;
            $sale->final_total = number_format($sale->final_total, 2,'.','');
            $now = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at)->format('Y-m-d');
            $sale->created_date = $now;
        }

        if($request->dashboard ){
            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('type_of_sale', function ($sales) {
                    if ($sales->type_of_sale == 1) return 'POS';
                    else return 'Marketplace';
                })
                ->editColumn('status', function ($sales) {
                    if ($sales->status == 'Partial Paid') return '<span class="badge badge-warning" >Partial Paid</span>';
                    elseif ($sales->status == 'Full Paid') return '<span class="badge badge-success">Full Paid</span>';
                    return ' <span class="badge badge-danger" >Full Due</span>';
                })
                ->setTotalRecords(10)
                ->rawColumns(['status','type_of_sale'])
                ->make(true);
        } else {
            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('type_of_sale', function ($sales) {
                    if ($sales->type_of_sale == 1) return 'POS';
                    else return 'Marketplace';
                })
                ->editColumn('status', function ($sales) {
                    if ($sales->status == 'Partial Paid') return '<span class="badge badge-warning" >Partial Paid</span>';
                    elseif ($sales->status == 'Full Paid') return '<span class="badge badge-success">Full Paid</span>';
                    return '<span class="badge badge-danger" >Full Due</span>';
                })
                ->editColumn('delivery_status', function ($sales) {
                    if ($sales->delivery_status == 'delivered') return '<span class="badge badge-success">Delivered</span>';
                    return '<span class="badge badge-danger" >Pending</span>';
                })
                ->rawColumns(['status','type_of_sale','delivery_status'])
                ->make(true);
        }

    }


    public function mostSaleProducts()
    {
        $title = 'Most Sale Products Report';
        $page_detail = 'Most Sale Products Report Details';
        return view('reports.sales.most_sale_products',compact('title', 'page_detail'));
    }
    public function mostSaleProductsByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cond = [];
        if(!empty(request()->brand_id) && !empty(request()->product_id)){
            $where = [['vendor_id', Auth::user()->vendor_id],['product_brand_id', request()->brand_id],['id', request()->product_id]];
        }elseif (!empty(request()->brand_id)){
            $where = [['vendor_id', Auth::user()->vendor_id],['product_brand_id', request()->brand_id]];
        }elseif(!empty(request()->product_id)){
            $where = [['vendor_id', Auth::user()->vendor_id],['id', request()->product_id]];
        }else{
            $where = [['vendor_id', Auth::user()->vendor_id]];
        }

        if(!empty($request->vendor_id) ){
            $cond = [['vendor_id', '=', $request->vendor_id ]];
        }
        if(!empty($request->start_date) && empty($request->end_date) ){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $cond = [['created_at', '>=', $start_date . ' 00:00:00']];
        }
        if(!empty($request->end_date) && empty($request->start_date) ){
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $cond = [['created_at', '<=', $end_date . ' 23:59:59']];
        }
        if(!empty($request->end_date) && !empty($request->start_date) ){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $cond = [['created_at', '>=', $start_date . ' 00:00:00'], ['created_at', '<=', $end_date . ' 23:59:59']];
        }

        $products = Product::with(['productCategory','productBrand','sale_details'=> function ($query) use($cond){
            $query->where($cond);
        }])->where($where)->latest()->get();
        $most_sale_products = [];
        foreach ($products as $product){
            $total= $product->sale_details->sum('quantity');
            if ($total > 0){
                $product->total_sale = $total;
                $most_sale_products[] = $product;
            }
        }
        return DataTables::of($most_sale_products)
            ->addIndexColumn()
            ->editColumn('category', function ($most_sale_product) {
                return $most_sale_product->productCategory->name;

            })
            ->editColumn('band', function ($most_sale_product) {
                return $most_sale_product->productBrand->name;

            })
            ->rawColumns(['category','band'])
            ->make(true);

    }

    public function customerSales(Request $request)
    {
        if (!request()->ajax()) {
            $title = 'Customer Sale Report';
            $page_detail = 'Customer Sale Report Details';
            return view('reports.sales.customer_sales',compact('title', 'page_detail'));
        }
        $query = Sale::with('posCustomer','marketplaceUser');
        if(!empty(request()->customer_id)){
            $customer_id = explode("_",$request->customer_id);
            if ($customer_id[0] == 'pos'){
                $query->where('pos_customer_id', $customer_id[1]);
            }elseif ($customer_id[0] == 'mp') {
                $query->where('marketplace_user_id', $customer_id[1]);
            }
        }
        if(!empty(request()->invoice_no)){
            $query->where('invoice_no', request()->invoice_no);
        }
        if(!empty(request()->status)){
            $query->where('status', request()->status);
        }
        if(!empty(auth()->user()->vendor_id)){
            $query->where('vendor_id', auth()->user()->vendor_id);
        }
        if(!empty(request()->start_date) && empty(request()->end_date) ){
            $query->where('created_at', '>=', request()->start_date . ' 00:00:00');
        }
        if(!empty(request()->end_date) && empty(request()->start_date) ){
            $query->where('created_at', '<=', request()->end_date . ' 23:59:59');
        }
        if(!empty(request()->end_date) && !empty(request()->start_date) ){
            $query->where('created_at', '>=', request()->start_date . ' 00:00:00');
            $query->where('created_at', '<=', request()->end_date . ' 23:59:59');
        }

        foreach ($query->get() as $dt) {
            $temp['invoice_no'] = $dt->invoice_no;
            if (($dt->type_of_sale == 1)){
                $temp['customer'] = $dt->posCustomer->name .'<br>'. $dt->posCustomer->email .'<br>'. $dt->posCustomer->phone;
            }else{
                $temp['customer'] = $dt->marketplaceUser->name .'<br>'. $dt->marketplaceUser->email .'<br>'. $dt->marketplaceUser->mobile;
            }
            $temp['items'] = $dt->items;
            if($dt->type_of_sale == 1){
                $temp['placed_from'] = 'POS';
            }else{
                $temp['placed_from'] = 'Marketplace';
            }
            if($dt->status == 'FP'){
                $temp['status'] = '<span class="badge badge-success" >Full Paid</span>';
            }else if($dt->status == 'PP'){
                $temp['status'] = '<span class="badge badge-warning" >Partial paid</span>';
            }else{
                $temp['status'] = '<span class="badge badge-info" >'.$dt->status.'</span>';
            }
            $temp['sale_date'] = Carbon::parse($dt->created_at)->format('d/m/y');
            $temp['final_price'] = number_format($dt->final_total, 2);
            $data[]=$temp;
        }
        if (!isset($data)){
            $data = array();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns(['customer','status'])
            ->make(true);
    }
    public function posMpCustomerAjax (Request $request)
    {
        if ($request->search){
            $pos_customers = PosCustomer::where('vendor_id',Auth::user()->vendor_id)->where('name','like',"%{$request->search}%")->get();
            $mp_customers = MarketplaceUser::where('name','like',"%{$request->search}%")->get();
        }else{
            $pos_customers = PosCustomer::where('vendor_id',Auth::user()->vendor_id)->paginate(5);
            $mp_customers = MarketplaceUser::paginate(5);
        }

        $customers = array();
        foreach ($pos_customers as $value){
            $customers['pos_'.$value->id] = $value->name .' ['. $value->email .']';
        }
        foreach ($mp_customers as $value){
            $customers['mp_'.$value->id] = $value->name .' ['. $value->email .']';
        }

        return $customers;
    }

    public function salesInvoices (Request $request)
    {
        if ($request->search){
            $sale_invoices = Sale::where('vendor_id',Auth::user()->vendor_id)->where('invoice_no','like',"%{$request->search}%")->get();
        }else{
            $sale_invoices = Sale::where('vendor_id',Auth::user()->vendor_id)->paginate(10);
        }

        $invoices = array();
        foreach ($sale_invoices as $value){
            $invoices[$value->invoice_no] = $value->invoice_no;
        }

        return $invoices;
    }
    public function salesStatus (Request $request)
    {
        if ($request->search){
            $sale_status = Sale::where('vendor_id',Auth::user()->vendor_id)->where('status','like',"%{$request->search}%")->get();
        }else{
            $sale_status = Sale::where('vendor_id',Auth::user()->vendor_id)->groupby('status')->distinct()->paginate(10);
        }

        $status = array();
        foreach ($sale_status as $value){
            $status[$value->status] = $value->status;
        }

        return $status;
    }
    public function itemWiseTotalSales(Request $request)
    {
        if (!$request->ajax())
        {
            $title        = 'Item Wise Sales';
            $page_detail  = 'List of Products Sales';
            $warehouses  = Warehouse::where('vendor_id',Auth::user()->vendor_id)->pluck('name', 'id')->toArray();

            return view('reports.sales.item_wise_sales', compact('title', 'page_detail','warehouses'));
        }
        $data = Sale::with(['saleDetails' => function($query) use ($request){
            if(!empty(request()->product_id)){
                $query->where('product_id', request()->product_id);
            }
            $query->with(['saleOrder']);
            $query->with(['productPoolDetails' => function($query) use ($request){
                $query->with(["productPool" => function($query) use ($request){
                    $query->with(["stockDetails"]);
                }]);
            }]);
            $query->with('product');
            $query->with('warehouse');
            $query->where('vendor_id', Auth::user()->vendor_id);
        }]);

        if(!empty($request->start_date) && empty($request->end_date) ){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $data->where('created_at', '>=', $start_date . ' 00:00:00');
        }
        if(!empty($request->end_date) && empty($request->start_date) ){
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $data->where('created_at', '<=', $end_date . ' 23:59:59');
        }
        if(!empty($request->end_date) && !empty($request->start_date) ){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $data->where('created_at', '>=', $start_date . ' 00:00:00');
            $data->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        $data = $data->get()->toArray();
        $pool = [];
        foreach($data as $sale) {
            foreach($sale['sale_details'] as $saleDetail){
                if(empty($saleDetail['sale_order'])){
                    continue;
                }
                if (!isset($pool[$saleDetail['product_id']]['quantity'])){
                    $pool[$saleDetail['product_id']]['quantity'] = 0;
                }
                if (!isset($pool[$saleDetail['product_id']]['total_price'])){
                    $pool[$saleDetail['product_id']]['total_price'] = 0;
                }
                if (!isset($pool[$saleDetail['product_id']]['stock'])){
                    $pool[$saleDetail['product_id']]['stock'] = 0;
                }
                $pool[$saleDetail['product_id']]['warehouse'] = !empty($saleDetail['warehouse']) ? $saleDetail['warehouse']['name'] : 'N/A';
                $pool[$saleDetail['product_id']]['name'] = $saleDetail['product']['name'];
                $pool[$saleDetail['product_id']]['quantity'] += $saleDetail['quantity'];
                $pool[$saleDetail['product_id']]['total_price'] += $saleDetail['total'];
                $qt = 0;
                foreach($saleDetail['product_pool_details']['product_pool']['stock_details'] as $psd){
                    $qt = $qt + $psd['available_quantity'];
                }
                $pool[$saleDetail['product_id']]['stock'] += $qt;
            }
        }

        return DataTables::of($pool)
            ->addIndexColumn()
            ->editColumn('total_price', function ($pool) {
                return number_format($pool['total_price'], 2);

            })
            ->rawColumns(['attribute','status','action', 'total_price', 'per_price'])
            ->make(true);
    }

    public function brandWiseTotalSales(Request $request)
    {
        if (!$request->ajax())
        {
            $title        = 'Brand Wise Sales';
            $page_detail  = 'List of Brand Wise Products Sales';
            return view('reports.sales.brand_wise_sales', compact('title', 'page_detail'));
        }
        $data = Sale::with(['saleDetails' => function($query) use ($request){
            $query->with(['product' => function($query) use ($request){
                if(!empty(request()->brand_id)){
                    $query->where('product_brand_id', request()->brand_id);
                }
                $query->with(["productBrand"]);
            }]);
            $query->with(['saleOrder']);
            $query->with(['productPoolDetails' => function($query) use ($request){
                $query->with(["productPool" => function($query) use ($request){
                    $query->with(["stockDetails"]);
                }]);
            }]);
            $query->with('warehouse');
            $query->where('vendor_id', Auth::user()->vendor_id);
        }]);

        if(!empty($request->start_date) && empty($request->end_date) ){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $data->where('created_at', '>=', $start_date . ' 00:00:00');
        }
        if(!empty($request->end_date) && empty($request->start_date) ){
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $data->where('created_at', '<=', $end_date . ' 23:59:59');
        }
        if(!empty($request->end_date) && !empty($request->start_date) ){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $data->where('created_at', '>=', $start_date . ' 00:00:00');
            $data->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        $data = $data->get()->toArray();
        $pool = [];
        foreach($data as $sale) {
            foreach($sale['sale_details'] as $saleDetail){
                if (!empty($saleDetail['product'])) {
                    if(empty($saleDetail['sale_order'])){
                    continue;
                    }
                    if (!isset($pool[$saleDetail['product_id']]['quantity'])){
                        $pool[$saleDetail['product_id']]['quantity'] = 0;
                    }
                    if (!isset($pool[$saleDetail['product_id']]['total_price'])){
                        $pool[$saleDetail['product_id']]['total_price'] = 0;
                    }
                    if (!isset($pool[$saleDetail['product_id']]['stock'])){
                        $pool[$saleDetail['product_id']]['stock'] = 0;
                    }
                        $pool[$saleDetail['product_id']]['warehouse'] = !empty($saleDetail['warehouse']) ? $saleDetail['warehouse']['name'] : 'N/A';
                        $pool[$saleDetail['product_id']]['brand_name'] = !empty($saleDetail['product']['product_brand']) ? $saleDetail['product']['product_brand']['name'] : 'N/A';
                        $pool[$saleDetail['product_id']]['name'] = $saleDetail['product']['name'] ?? '';
                        $pool[$saleDetail['product_id']]['quantity'] += $saleDetail['quantity'];
                        $pool[$saleDetail['product_id']]['total_price'] += $saleDetail['total'];
                        $qt = 0;
                        foreach ($saleDetail['product_pool_details']['product_pool']['stock_details'] as $psd) {
                            $qt = $qt + $psd['available_quantity'];
                        }
                        $pool[$saleDetail['product_id']]['stock'] += $qt;
                }
            }
        }

        return DataTables::of($pool)
            ->addIndexColumn()
            ->editColumn('total_price', function ($pool) {
                return number_format($pool['total_price'], 2) ;
            })
            ->rawColumns(['attribute','status','action', 'total_price', 'per_price'])
            ->make(true);
    }

    public function customerWiseDue(Request $request)
    {
        if (!request()->ajax()) {
            $title = 'Customer Due Report';
            $page_detail = 'Customer Due Report Details';
            return view('reports.dues.customer_dues',compact('title', 'page_detail'));
        }
        $query = Sale::with('posCustomer','marketplaceUser');
        if(!empty(request()->customer_id)){
            $customer_id = explode("_",$request->customer_id);
            if ($customer_id[0] == 'pos'){
                $query->where('pos_customer_id', $customer_id[1]);
            }elseif ($customer_id[0] == 'mp') {
                $query->where('marketplace_user_id', $customer_id[1]);
            }
        }
        $query->with('payment');
        if(!empty(auth()->user()->vendor_id)){
            $query->where('vendor_id', auth()->user()->vendor_id);
        }

        $data = array();
        foreach ($query->get() as $dt) {
            if (($dt->type_of_sale == 1)){
                $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['customer'] = $dt->posCustomer->name .'<br>'. $dt->posCustomer->email .'<br>'. $dt->posCustomer->phone;
                if (!isset($data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['items'])){
                    $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['items'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['items'] += $dt->items;

                if (!isset($data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['final_price'])){
                    $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['final_price'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['final_price'] += $dt->final_total;

                if (!isset($data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['total_payment'])){
                    $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['total_payment'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['total_payment'] += $dt->total_payment;

                if (!isset($data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['due_amount'])){
                    $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['due_amount'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['due_amount'] += $dt->due_payment;
                $data[$dt->type_of_sale.'_'.$dt->pos_customer_id]['placed_from'] = 'POS';
            }else{
                $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['customer'] = $dt->marketplaceUser->name .'<br>'. $dt->marketplaceUser->email .'<br>'. $dt->marketplaceUser->mobile;
                if (!isset($data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['items'])){
                    $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['items'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['items'] += $dt->items;

                if (!isset($data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['final_price'])){
                    $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['final_price'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['final_price'] += $dt->final_total;

                if (!isset($data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['total_payment'])){
                    $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['total_payment'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['total_payment'] += $dt->total_payment;

                if (!isset($data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['due_amount'])){
                    $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['due_amount'] = 0;
                }
                $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['due_amount'] += $dt->due_payment;
                $data[$dt->type_of_sale.'_'.$dt->marketplace_user_id]['placed_from'] = 'Marketplace';
            }
        }
        if (!isset($data)){
            $data = array();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('final_price', function ($data) {
                return number_format($data['final_price'], 2);

            })
            ->editColumn('due_amount', function ($data) {
                return number_format($data['due_amount'], 2);

            })
            ->rawColumns(['customer','final_price','total_payment','due_amount'])
            ->make(true);
    }

    public function salesWiseDue(Request $request)
    {
        if (!request()->ajax()) {
            $customer_query_data = array();
            $customer_query_id = 0;
            $pos_customer_id = request()->has('pos_customer_id') ? request()->query('pos_customer_id') : 0;
            $mp_customer_id = request()->has('mp_customer_id') ? request()->query('mp_customer_id') : 0;
            if (!empty($pos_customer_id)){
                $pos_customer = PosCustomer::where('id',$pos_customer_id)->first();
                $customer_query_id = $pos_customer->id;
                $customer_query_data['key'] = 'pos_'.$pos_customer->id;
                $customer_query_data['value'] = $pos_customer->name .' ['. $pos_customer->email .']';
            }else
            if (!empty($mp_customer_id)){
                $mp_customer = MarketplaceUser::where('id',$mp_customer_id)->first();
                $customer_query_id = $mp_customer->id;
                $customer_query_data['key'] = 'mp_'.$mp_customer->id;
                $customer_query_data['value'] = $mp_customer->name .' ['. $mp_customer->email .']';
            }
            $title = 'Sale Order Wise Due Report';
            $page_detail = 'Sale Order Wise Due Details';
            return view('reports.dues.sales_wise_dues',compact('title', 'page_detail','customer_query_id','customer_query_data'));
        }
        $query = Sale::with('posCustomer','marketplaceUser');
        if(!empty(request()->customer_id)){
            $customer_id = explode("_",$request->customer_id);
            if ($customer_id[0] == 'pos'){
                $query->where('pos_customer_id', $customer_id[1]);
            }elseif ($customer_id[0] == 'mp') {
                $query->where('marketplace_user_id', $customer_id[1]);
            }
        }
        if(!empty(request()->invoice_no)){
            $query->where('invoice_no', request()->invoice_no);
        }
        if(!empty(request()->status)){
            $query->where('status', request()->status);
        }
        if(!empty(auth()->user()->vendor_id)){
            $query->where('vendor_id', auth()->user()->vendor_id);
        }
        if(!empty(request()->end_date) && !empty(request()->start_date)){
            $start_date = date('Y-m-d',strtotime(request()->start_date));
            $end_date = date('Y-m-d',strtotime(request()->end_date));
            $query->where('created_at', '>=', $start_date . ' 00:00:00');
            $query->where('created_at', '<=', $end_date . ' 23:59:59');
        }
        $query->with('payment');

        foreach ($query->latest()->get() as $dt) {
            $temp['invoice_no'] = $dt->invoice_no;
            if (($dt->type_of_sale == 1)){
                $temp['customer'] = $dt->posCustomer->name .'<br>'. $dt->posCustomer->email .'<br>'. $dt->posCustomer->phone;
            }else{
                $temp['customer'] = $dt->marketplaceUser->name .'<br>'. $dt->marketplaceUser->email .'<br>'. $dt->marketplaceUser->mobile;
            }
            $temp['items'] = $dt->items;
            if($dt->type_of_sale == 1){
                $temp['placed_from'] = 'POS';
            }else{
                $temp['placed_from'] = 'Marketplace';
            }
            if($dt->status == 'FP'){
                $temp['status'] = '<span class="badge badge-success" >Full Paid</span>';
            }else if($dt->status == 'PP'){
                $temp['status'] = '<span class="badge badge-warning" >Partial paid</span>';
            }else{
                $temp['status'] = '<span class="badge badge-info" >'.$dt->status.'</span>';
            }
            $temp['sale_date'] = Carbon::parse($dt->created_at)->format('d/m/y');
            $temp['final_price'] = number_format($dt->final_total, 2);
            $temp['total_payment'] = number_format($dt->total_payment,2);
            $temp['due_amount'] = number_format($dt->due_payment,2);
            $data[]=$temp;
        }
        if (!isset($data)){
            $data = array();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns(['customer','status'])
            ->make(true);
    }

    public function brandWiseDue(Request $request)
    {
        if (!request()->ajax()) {
            $title = 'Brand Wise Due Report';
            $page_detail = 'Brand Wise Due Report Details';
            return view('reports.dues.brand_wise_dues', compact('title', 'page_detail'));
        }
        $query = Sale::with(['saleDetails' => function ($query) use ($request) {
            $query->with(['product' => function ($query) use ($request) {
                if (!empty($request->brand_id)) {
                    $query->where('product_brand_id', $request->brand_id);
                }
                $query->with(["productBrand"]);
            }]);
        }]);
        if (!empty(auth()->user()->vendor_id)) {
            $query->where('vendor_id', auth()->user()->vendor_id);
        }
        $query->with('payment');
        $temp = [];
        $brand_id = 0;
        foreach ($query->latest()->get() as $dt) {
            foreach ($dt->saleDetails as $saleDetail) {
                if (!empty($saleDetail['product'])) {
                    if (!empty($saleDetail->product->productBrand)) {
                        $brand_id = $saleDetail['product']['product_brand_id'];
                        if (!empty($brand_id)) {
                            $temp[$brand_id]['brand'] = !empty($saleDetail['product']['productBrand']) ? $saleDetail['product']['productBrand']['name'] : 'N/A';
                        }
                    }
                    if (!empty($brand_id)) {
                        if (!isset($temp[$brand_id]['final_price'])) {
                            $temp[$brand_id]['final_price'] = 0;
                        }
                        if (!isset($temp[$brand_id]['total_payment'])) {
                            $temp[$brand_id]['total_payment'] = 0;
                        }
                        if (!isset($temp[$brand_id]['due_amount'])) {
                            $temp[$brand_id]['due_amount'] = 0;
                        }
                        $temp[$brand_id]['final_price'] += $dt->final_total ?? 0;
                        $temp[$brand_id]['total_payment'] += $dt->total_payment ?? 0;
                        $temp[$brand_id]['due_amount'] += $dt->due_payment ?? 0;
                    }
                }
            }
        }
        return DataTables::of($temp)
            ->addIndexColumn()
            ->editColumn('final_price', function ($temp) {
                return number_format($temp['final_price'], 2);
            })
            ->editColumn('total_payment', function ($temp) {
                return number_format($temp['total_payment'], 2);
            })
            ->editColumn('due_amount', function ($temp) {
                return number_format($temp['due_amount'], 2);
            })
            ->rawColumns(['brand', 'final_price', 'total_payment', 'due_amount'])
            ->make(true);

    }


    public function customerWiseProductBuy(Request $request)
    {
        if (!request()->ajax()) {
            $title = 'Customer Wise Product Buy Report';
            $page_detail = 'Customer Wise Product Buy Report';
            return view('reports.sales.customer_wise_products_buy',compact('title', 'page_detail'));
        }
        $queries = Sale::with(['posCustomer','marketplaceUser','saleDetails'=> function ($query) {
            $query->with(['product' => function ($query) {
                if(!empty(request()->brand_id)){
                    $query->where('product_brand_id', request()->brand_id);
                }
                if(!empty(request()->category_id)){
                    $query->where('product_category_id', request()->category_id);
                }
                if(!empty(request()->product_id)){
                    $query->where('id', request()->product_id);
                }
                $query->with('productBrand','productCategory');
            }]);
        }]);
        if(!empty(request()->customer_id)){
            $customer_id = explode("_",$request->customer_id);
            if ($customer_id[0] == 'pos'){
                $queries->where('pos_customer_id', $customer_id[1]);
            }elseif ($customer_id[0] == 'mp') {
                $queries->where('marketplace_user_id', $customer_id[1]);
            }
        }
        if(!empty(auth()->user()->vendor_id)){
            $queries->where('vendor_id', auth()->user()->vendor_id);
        }

        $temp = array();
        foreach ($queries->get() as $dt) {
            foreach ($dt->saleDetails as $saleDetail) {
                if (empty($saleDetail->product)){
                    continue;
                }
                if (($dt->type_of_sale == 1)) {
                    $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['customer'] = $dt->posCustomer->name . '<br>' . $dt->posCustomer->email . '<br>' . $dt->posCustomer->phone;
                    $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['customer_type'] = 'POS';
                    $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['product'] = $saleDetail->product->name;
                    $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['product_brand'] = $saleDetail->product->productBrand->name;
                    $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['product_category'] = $saleDetail->product->productCategory->name;
                    if (isset($temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['total_sale'])){
                        $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['total_sale'] += $saleDetail->quantity;
                    }else{
                        $temp['pos_'.$dt->pos_customer_id.'_'.$saleDetail->product_id]['total_sale'] = $saleDetail->quantity;
                    }
                } else {
                    $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['customer'] = $dt->marketplaceUser->name . '<br>' . $dt->marketplaceUser->email . '<br>' . $dt->marketplaceUser->mobile;
                    $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['customer_type'] = 'Marketplace';
                    $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['product'] = $saleDetail->product->name;
                    $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['product_brand'] = $saleDetail->product->productBrand->name;
                    $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['product_category'] = $saleDetail->product->productCategory->name;
                    if (isset($temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['total_sale'])){
                        $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['total_sale'] += $saleDetail->quantity;
                    }else{
                        $temp['mp_'.$dt->marketplace_user_id.'_'.$saleDetail->product_id]['total_sale'] = $saleDetail->quantity;
                    }
                }
            }
        }
        if (!isset($temp)){
            $temp = array();
        }
        return DataTables::of($temp)
            ->addIndexColumn()
            ->rawColumns(['customer'])
            ->make(true);
    }
    public function profitLoss(Request $request)
    {
        if (!request()->ajax()) {
            $title = 'Profit Loss Report';
            $page_detail = 'Profit Loss Report';
            return view('reports.sales.profit_loss',compact('title', 'page_detail'));
        }
        $query = SaleDetail::with(['warehouse','product' => function($query) use ($request){
            if(!empty(request()->brand_id)){
                $query->where('product_brand_id', request()->brand_id);
            }
        },'sale'=> function ($query) {
            $query->with('posCustomer');
            $query->with('marketplaceUser');
        }]);
        if(!empty(request()->product_id)){
            $query->where('product_id', request()->product_id);
        }
        if(!empty($request->end_date) && !empty($request->start_date)){
            $start_date = date('Y-m-d',strtotime($request->start_date));
            $end_date = date('Y-m-d',strtotime($request->end_date));
            $query->where('created_at', '>=', $start_date . ' 00:00:00');
            $query->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        foreach ($query->get() as $dt) {
            if (!empty($dt->product)) {
                if (!empty($dt->sale)) {
                    $temp['invoice_no'] = $dt->sale->invoice_no ? $dt->sale->invoice_no : '';
                    if (($dt->sale->type_of_sale == 1)) {
                        $temp['customer'] = $dt->sale->posCustomer->name . '<br>' . $dt->sale->posCustomer->email . '<br>' . $dt->sale->posCustomer->phone;
                    } else {
                        $temp['customer'] = $dt->sale->marketplaceUser->name . '<br>' . $dt->sale->marketplaceUser->email . '<br>' . $dt->sale->marketplaceUser->mobile;
                    }
                    if ($dt->sale->type_of_sale == 1) {
                        $temp['placed_from'] = 'POS';
                    } else {
                        $temp['placed_from'] = 'Marketplace';
                    }
                } else {
                    $temp['invoice_no'] = '';
                    $temp['customer'] = '';
                    $temp['placed_from'] = '';
                }

                $temp['quantity'] = $dt->quantity;
                $temp['total_cumulative_purchase_price'] = $dt->total_cumulative_purchase_price;
                $temp['total_cumulative_sold_price'] = $dt->total_cumulative_sold_price;
                $temp['total_cumulative_profit'] = $dt->total_cumulative_profit;
                if (!empty($dt->warehouse)) {
                    $temp['warehouse_name'] = $dt->warehouse->name;
                } else {
                    $temp['warehouse_name'] = '';
                }
                if (!empty($dt->product)) {
                    $temp['product_name'] = $dt->product->name;
                } else {
                    $temp['product_name'] = '';
                }

                $temp['sale_date'] = Carbon::parse($dt->created_at)->format('d/m/y');
                $data[] = $temp;
            }
        }
        if (!isset($data)){
            $data = array();
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->rawColumns(['customer','status'])
            ->make(true);
    }


}
