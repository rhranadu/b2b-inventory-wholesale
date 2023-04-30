<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductStock;
use App\Purchase;
use App\Sale;
use App\SaleDetail;
use App\StockDetail;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Calculation\Category;
use Yajra\DataTables\DataTables;

class ProductStockReportController extends Controller
{

    public function index()
    {
        $title       = 'Product Stocks Report';
        $page_detail = 'Product Stocks Report Details';
        $vendors     = Vendor::select('id', 'name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.products.stock', compact('vendors', 'title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $cond  = [];
        $where = [['vendor_id', Auth::user()->vendor_id]];
        if (!empty($request->vendor_id)) {
            $cond = [['vendor_id', '=', $request->vendor_id]];
        }
        if (!empty($request->startDate) && empty($request->endDate)) {
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00']];
        }
        if (!empty($request->endDate) && empty($request->startDate)) {
            $cond = [['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        if (!empty($request->endDate) && !empty($request->startDate)) {
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00'], ['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        $where          = array_merge($where, $cond);
        $product_stocks = ProductStock::with('productStockDetails', 'purchaseSupplier')->where($where)->latest()->get();

        foreach ($product_stocks as $product_stock) {
            $product_stock->productStockDetailCount   = count($product_stock->productStockDetails);
            $product_stock->productStockInvoiceAmount = $product_stock->productStockDetails->sum('total_price');
        }

        return DataTables::of($product_stocks)
            ->addIndexColumn()
            ->addColumn('invoice_no', function ($product_stocks) {
                return '<a href="/admin/purchase/' . $product_stocks->purchase_id . '" target="_blank">' . $product_stocks->invoice_no . '</a>';
            })
            ->rawColumns(['invoice_no'])
            ->make(true);
//        return view('reports.purchases.invoice_report_ajax', compact('purchases'));
    }

    public function categoryStockReport(Request $request)
    {
        if (!$request->ajax()) {
            $title       = 'Product Sales Report';
            $page_detail = 'Product Sales Report Details';
            $vendors     = Vendor::select('id', 'name')
                ->where([
                    ['status', 1],
                ])
                ->get();
            return view('reports.stock.category_stock', compact('vendors', 'title', 'page_detail'));
        }

        $cond  = [];
        $where = [['vendor_id', Auth::user()->vendor_id]];
        if (!empty($request->vendor_id)) {
            $cond = [['vendor_id', '=', $request->vendor_id]];
        }
        if (!empty($request->startDate) && empty($request->endDate)) {
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00']];
        }
        if (!empty($request->endDate) && empty($request->startDate)) {
            $cond = [['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        if (!empty($request->endDate) && !empty($request->startDate)) {
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00'], ['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        if (!empty($request->warehouse)) {
            $cond = [['warehouse_id', $request->warehouse]];
        }
        $where = array_merge($where, $cond);

        $sales = Sale::with('saleWarehouse', 'posCustomer', 'payment')->where($where)->latest()->get();

        foreach ($sales as $sale) {
            if (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'PP') {
                $sale->status = 'Partial Paid';
            } elseif (isset($sale->payment->last()->status) && $sale->payment->last()->status == 'FP') {
                $sale->status = 'Full Paid';
            } else {
                $sale->status = 'Full Due';
            }
            if ($sale->saleWarehouse) {
                $sale->saleWarehouseName = $sale->saleWarehouse->name;
            } else {
                $sale->saleWarehouseName = 'N/A';
            }
            if (isset($sale->payment->last()->due_amount)) {
                $sale->due_amount = $sale->payment->last()->due_amount;
            }
            $sale->pay_amount   = $sale->final_total - $sale->due_amount;
            $sale->final_total  = number_format($sale->final_total, 2, '.', '');
            $now                = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at)->format('Y-m-d');
            $sale->created_date = $now;
        }

        if ($request->dashboard) {
            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('type_of_sale', function ($sales) {
                    if ($sales->type_of_sale == 1) {
                        return 'POS';
                    } else {
                        return 'Marketplace';
                    }

                })
                ->editColumn('status', function ($sales) {
                    if ($sales->status == 'Partial Paid') {
                        return '<span class="badge badge-warning" >Partial Paid</span>';
                    } elseif ($sales->status == 'Full Paid') {
                        return '<span class="badge badge-success">Full Paid</span>';
                    }

                    return ' <span class="badge badge-danger" >Full Due</span>';
                })
                ->setTotalRecords(10)
                ->rawColumns(['status', 'type_of_sale'])
                ->make(true);
        } else {
            return DataTables::of($sales)
                ->addIndexColumn()
                ->editColumn('type_of_sale', function ($sales) {
                    if ($sales->type_of_sale == 1) {
                        return 'POS';
                    } else {
                        return 'Marketplace';
                    }

                })
                ->editColumn('status', function ($sales) {
                    if ($sales->status == 'Partial Paid') {
                        return '<span class="badge badge-warning" >Partial Paid</span>';
                    } elseif ($sales->status == 'Full Paid') {
                        return '<span class="badge badge-success">Full Paid</span>';
                    }

                    return '<span class="badge badge-danger" >Full Due</span>';
                })
                ->rawColumns(['status', 'type_of_sale'])
                ->make(true);
        }

    }

    public function stockDetail(Request $request)
    {
        if (!request()->ajax()) {
            $title       = 'Stock Detail Report';
            $page_detail = 'Product Stock Details Report';
            return view('reports.stock.details', compact('title', 'page_detail'));
        }
        $query = StockDetail::with(['warehouse','warehouseDetail','purchasesDetailsStatus',
            'product' => function($query) use ($request) {
                if (!empty(request()->brand_id)) {
                    $query->where('product_brand_id', request()->brand_id);
                }
            }])
        ->with(['productStock' => function($query){
            if(!empty(request()->invoice_no)){
                $query->where('invoice_no', trim(request()->invoice_no));
            }
            if(!empty(request()->supplier_id)){
                $query->where('supplier_id', request()->supplier_id);
            }
        }])
        ->with(['productPoolDetails' => function($query){
            $query->with('productPool');
        }]);
        if(!empty(request()->product_id)){
            $query->where('product_id', request()->product_id);
        }
        if(!empty(request()->warehouse_detail_id)){
            $query->where('warehouse_detail_id', request()->warehouse_detail_id);
        }
        if(!empty(request()->warehouse_id)){
            $query->where('warehouse_id', request()->warehouse_id);
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


        // dd($query->get()->toArray());
        $data = [];
        foreach ($query->get() as $dt) {
            if (!empty($dt->product)) {
                if (empty($dt->productStock)) {
                    continue;
                }
                $temp['product_name'] = $dt->product->name;
                $temp['attribute'] = $dt->purchasesDetailsStatus->product_attributes;
                $temp['warehouse'] = $dt->warehouse->name;
                if (!empty($dt->warehouseDetail)) {
                    $temp['warehouse_section'] = $dt->warehouseDetail->name;
                } else {
                    $temp['warehouse_section'] = 'N/A';
                }
                $temp['stocked_quantity'] = $dt->productPoolDetails->stock_quantity;
                $temp['available_stock'] = $dt->productPoolDetails->available_quantity;
                $temp['purchase_invoice'] = $dt->productStock->invoice_no;
                $temp['purchase_price'] = number_format($dt->purchasesDetailsStatus->price, 2);
                $temp['purchased_at'] = Carbon::parse($dt->purchasesDetailsStatus->created_at)->format('d/m/y');
                $temp['stock_per_price'] = number_format($dt->price, 2);
                $temp['stocked_at'] = Carbon::parse($dt->created_at)->format('d/m/y');
                $data[] = $temp;
            }
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('attribute', function ($data) {
                return $data['attribute'];
            })
            ->rawColumns(['attribute'])
            ->make(true);
    }
    public function categoryStockDetail()
    {
        if (!request()->ajax()) {
            $title       = 'Stock Detail Report';
            $page_detail = 'Category Product Stock Details Report';
            return view('reports.stock.category_products_details', compact('title', 'page_detail'));
        }
        $query = StockDetail::with('warehouse','warehouseDetail','purchasesDetailsStatus')
            ->with(['product' => function($query){
                if(!empty(request()->category_id)) {
                    $query->where('product_category_id',request()->category_id);
                    $category = Product::where('product_category_id',request()->category_id)->where('vendor_id',Auth::user()->vendor_id)->get();
                    if (!empty($category)){
                        $query->with('productCategory');
                    }
                }else{
                    $query->with('productCategory');
                }
                if (!empty(request()->brand_id)) {
                    $query->where('product_brand_id', request()->brand_id);
                }
            }])
            ->with(['productStock' => function($query){
                if(!empty(request()->invoice_no)){
                    $query->where('invoice_no', trim(request()->invoice_no));
                }
                if(!empty(request()->supplier_id)){
                    $query->where('supplier_id', request()->supplier_id);
                }
            }])
            ->with(['productPoolDetails' => function($query){
                $query->with('productPool');
            }]);
        if(!empty(request()->product_id)){
            $query->where('product_id', request()->product_id);
        }
        if(!empty(request()->warehouse_detail_id)){
            $query->where('warehouse_detail_id', request()->warehouse_detail_id);
        }
        if(!empty(request()->warehouse_id)){
            $query->where('warehouse_id', request()->warehouse_id);
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

//         dd($query->get()->toArray());
        $data = [];
        foreach ($query->get() as $dt) {
            if(empty($dt->productStock)){
                continue;
            }
            if(empty($dt->product)){
                continue;
            }
            $temp['category'] = $dt->product->productCategory->name;
            $temp['product_name'] = $dt->product->name;
            $temp['attribute'] = $dt->purchasesDetailsStatus->product_attributes;
            $temp['warehouse'] = $dt->warehouse->name;
            if(!empty($dt->warehouseDetail)){
                $temp['warehouse_section'] = $dt->warehouseDetail->name;
            }else{
                $temp['warehouse_section'] = 'N/A';
            }
            $temp['stocked_quantity'] = $dt->productPoolDetails->stock_quantity;
            $temp['available_stock'] = $dt->productPoolDetails->available_quantity <= 0 ? 0 : $dt->productPoolDetails->available_quantity;
            $temp['purchase_invoice'] = $dt->productStock->invoice_no;
            $temp['purchase_price'] = number_format($dt->purchasesDetailsStatus->price, 2);
            $temp['purchased_at'] = Carbon::parse($dt->purchasesDetailsStatus->created_at)->format('d/m/y');
            $temp['stock_per_price'] = number_format($dt->price, 2);
            $temp['stocked_at'] = Carbon::parse($dt->created_at)->format('d/m/y');
            $data[]=$temp;
        }
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('attribute', function ($data) {
                return $data['attribute'];
            })
            ->editColumn('available_stock', function ($data) {
                return $data['available_stock'];
            })
            ->rawColumns(['attribute'])
            ->make(true);
    }

    public function categorySearchByAjax(Request $request)
    {
        $search = $request->search;
        $categories = ProductCategory::where('name', 'like', '%' .$search . '%')
            ->select('name','id')
            ->get();
        $response = array();
        foreach($categories as $category){
            $response[$category->id] = $category->name;
        }
        echo json_encode($response);
        exit;
    }

    public function brandSearchByAjax(Request $request)
    {
        $search = $request->search;
        $brands = ProductBrand::where('status',1)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->where('name', 'like', '%' .$search . '%')
            ->select('name','id')
            ->get();
        $response = array();
        foreach($brands as $brand){
            $response[$brand->id] = $brand->name;
        }
        echo json_encode($response);
        exit;
    }
    public function categoryWiseStockDetail()
    {
        if (!request()->ajax()) {
            $title       = 'Stock Detail Report';
            $page_detail = 'Category Wise Stock Details Report';
            return view('reports.stock.category_wise_details', compact('title', 'page_detail'));
        }
        if (!empty(request()->category_id)) {
            $categoryIds = ProductCategory::where(['status'=>1,'is_approved'=>1,'id'=>request()->category_id])->pluck('id');
        }else{
            $categoryIds = ProductCategory::where(['status'=>1,'is_approved'=>1])->pluck('id');
        }
        $productIds = array();

        if (!empty(request()->brand_id)) {
            $pIds = Product::select('id','product_category_id')->whereIn('product_category_id',$categoryIds)->where('product_brand_id',request()->brand_id)->where('vendor_id',Auth::user()->vendor_id)->get();
        }else{
            $pIds = Product::select('id','product_category_id')->whereIn('product_category_id',$categoryIds)->where('vendor_id',Auth::user()->vendor_id)->get();
        }
        foreach ($pIds as $pId){
            $productIds[$pId['product_category_id']][] = $pId['id'];
        }
        $datas = array();
        foreach ($productIds as $key => $value) {
            $query = StockDetail::whereIn('product_id', $value)->with('purchasesDetailsStatus','warehouse','warehouseDetail')

                ->with(['productStock' => function ($query) {
                    if (!empty(request()->invoice_no)) {
                        $query->where('invoice_no', trim(request()->invoice));
                    }
                    if (!empty(request()->supplier_id)) {
                        $query->where('supplier_id', request()->supplier_id);
                    }
                }])
                ->with(['productPoolDetails' => function ($query) {
                    $query->with('productPool');
                }]);

            if (!empty(auth()->user()->vendor_id)) {
                $query->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty(request()->warehouse_detail_id)){
                $query->where('warehouse_detail_id', request()->warehouse_detail_id);
            }
            if(!empty(request()->warehouse_id)){
                $query->where('warehouse_id', request()->warehouse_id);
            }
            $datas[$key] = ProductCategory::select('id','name')->where(['status'=>1,'is_approved'=>1,'id'=>$key])->first();;
            $datas[$key]['stock'] = $query->get();
        }

        $temp = array();
        if (!empty($datas)) {
            foreach ($datas as $k => $v) {
                foreach ($v['stock'] as $dt) {
                    if (empty($dt->productStock)) {
                        continue;
                    }
                    if (empty($dt->product)) {
                        continue;
                    }
                    $w_d_id = $dt->warehouse_detail_id != null ? $dt->warehouse_detail_id : 0;
                    $temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['warehouse'] = $dt->warehouse->name;
                    if(!empty($dt->warehouseDetail)){
                        $temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['warehouse_section'] = $dt->warehouseDetail->section_name;
                    }else{
                        $temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['warehouse_section'] = 'N/A';
                    }
                    $temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['category'] = $v->name;
                    if (isset($temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['stocked_quantity'])) {
                        $temp[$k.'_'.$dt->warehouse_id . '_' . $w_d_id]['stocked_quantity'] += $dt->productPoolDetails->stock_quantity;
                    }else{
                        $temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['stocked_quantity'] = $dt->productPoolDetails->stock_quantity;
                    }
                    if (isset($temp[$k.'_'.$dt->warehouse_id.'_'.$w_d_id]['available_stock'])) {
                        $temp[$k.'_'.$dt->warehouse_id . '_' . $w_d_id]['available_stock'] += $dt->productPoolDetails->available_quantity;
                    }else{
                        $temp[$k.'_'.$dt->warehouse_id . '_' . $w_d_id]['available_stock'] = $dt->productPoolDetails->available_quantity;
                    }
                }
            }
        }
        return DataTables::of($temp)
            ->addIndexColumn()
            ->make(true);
    }

    public function warehouseWiseStock()
    {
        if (!request()->ajax()) {
            $title       = 'Warehouse Stock Report';
            $page_detail = 'Warehouse Wise Stock Report';
            return view('reports.stock.warehouse_wise_stock', compact('title', 'page_detail'));
        }

        $query = StockDetail::with('productPoolDetails','warehouse');


        if (!empty(auth()->user()->vendor_id)) {
            $query->where('vendor_id', auth()->user()->vendor_id);
        }
        if(!empty(request()->warehouse_id)){
            $query->where('warehouse_id', request()->warehouse_id);
        }
        $datas = $query->get();

        $temp = array();
        if (!empty($datas)) {
            foreach ($datas as $k => $v) {

                $temp[$v->warehouse_id]['warehouse'] = $v->warehouse->name;
                $temp[$v->warehouse_id]['warehouse_id'] = $v->warehouse_id;

                if (isset($temp[$v->warehouse_id]['stocked_quantity'])) {
                    $temp[$v->warehouse_id]['stocked_quantity'] += $v->productPoolDetails->stock_quantity;
                }else{
                    $temp[$v->warehouse_id]['stocked_quantity'] = $v->productPoolDetails->stock_quantity;
                }
                if (isset($temp[$v->warehouse_id]['available_stock'])) {
                    $temp[$v->warehouse_id ]['available_stock'] += $v->productPoolDetails->available_quantity;
                }else{
                    $temp[$v->warehouse_id ]['available_stock'] = $v->productPoolDetails->available_quantity;
                }
            }
        }
        return DataTables::of($temp)
            ->addIndexColumn()
            ->editColumn('sold_quantity', function ($temp) {
                $sold_quantity = SaleDetail::where('warehouse_id',$temp['warehouse_id'])->sum('quantity');
                return $sold_quantity;
            })
            ->rawColumns(['sold_quantity'])
            ->make(true);
    }

    public function productWiseWarehouseStockDetail()
    {
        if (!request()->ajax()) {
            $title       = 'Product Wise Warehouse Stock Report';
            $page_detail = 'Product Wise Warehouse Stock';
            return view('reports.stock.product-wise-warehouse-stock-detail', compact('title', 'page_detail'));
        }

        $product_id = request()->has('product_id') ? request()->product_id : 0;
        if(!empty($product_id)) {
            $query = Product::with(['stock' => function ($q) {
                $q->where('status', 'available');
                $q->with('warehouse', 'warehouseDetail','productPoolDetails');
            }]);

            if (!empty(auth()->user()->vendor_id)) {
                $query->where('vendor_id', auth()->user()->vendor_id);
            }
            if (!empty(request()->brand_id)) {
                $query->where('product_brand_id', request()->brand_id);
            }
            if (!empty($product_id)) {
                $query->where('id', $product_id);
            }
//        if(!empty(request()->warehouse_id)){
//            $query->where('warehouse_id', request()->warehouse_id);
//        }
            $datas = $query->get();
            //dd($datas->toArray());
            $temp = array();
            if (!empty($datas)) {
                foreach ($datas as $key => $value) {
                    foreach ($value->stock as $k => $v) {
                        if ($v->productPoolDetails->available_quantity > 0) {
                            $temp[$value->id . '_' . $v->id]['product_name'] = $value->name;
                            $temp[$value->id . '_' . $v->id]['warehouse_id'] = $v->warehouse_id;
                            $temp[$value->id . '_' . $v->id]['warehouse_name'] = $v->warehouse->name;
                            $temp[$value->id . '_' . $v->id]['warehouse_section_name'] =$v->warehouseDetail->section_name ?? '';
                            $temp[$value->id . '_' . $v->id]['warehouse_section_id'] =$v->warehouseDetail->id?? 0;

                            if (isset($temp[$v->warehouse_id]['stocked_quantity'])) {
                                $temp[$value->id . '_' . $v->id]['stocked_quantity'] += $v->productPoolDetails->stock_quantity;
                            } else {
                                $temp[$value->id . '_' . $v->id]['stocked_quantity'] =
                                    $v->productPoolDetails->stock_quantity;
                            }
                            if (isset($temp[$value->id . '_' . $v->id]['available_stock'])) {
                                $temp[$value->id . '_' . $v->id]['available_stock'] += $v->productPoolDetails->available_quantity;
                            } else {
                                $temp[$value->id . '_' . $v->id]['available_stock'] =
                                    $v->productPoolDetails->available_quantity;
                            }
                        }
                    }
                }
            }
        }
        $temp = $temp?? [];

        return DataTables::of($temp)
            ->addIndexColumn()
            ->editColumn('action', function ($temp) {
                if (empty($temp['warehouse_section_id'])) {
                    return '<span class="badge badge-info" onclick="warehouseJstree(' . $temp['warehouse_id'] . ')">Detail</span>';
                }else{
                    return '<span class="badge badge-info" onclick="warehouseSectionJstree(' . $temp['warehouse_section_id'] . ')">Detail</span>';
                }
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
