<?php

namespace App\Http\Controllers;

use App\MarketplaceUserAddress;
use App\Product;
use App\ProductPool;
use App\SaleOrder;
use App\Sale;
use App\SaleDetail;
use App\Warehouse;
use App\ProductPoolSaleDetail;
use App\ProductPoolStockDetail;
use App\StockedProductBarcode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;


class SaleOrderController extends Controller {


    public function index(Request $request) {
        if (!$request->ajax())
        {
            $title        = 'Sale Orders';
            $page_detail  = 'List of Sale Orders';
            return view('sale_orders.pos_warehouse', compact('title', 'page_detail'));
        }

        $data = Sale::with(['saleDetails' => function($query) use ($request){
            $query->with(['saleOrder' => function($query) use ($request){
                if(!empty($request->order_status)){
                    $query->where('status', '=', trim($request->order_status));
                }
                // if(empty($request->order_status)){
                //     $query->where(function($query) {
                //         if(!empty(auth()->user()->warehouse_id)){
                //             $query->where('warehouse_id', auth()->user()->warehouse_id);
                //         }
                //         $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
                //     });
                // }
                // if(!empty($request->order_status) && trim($request->order_status) == 'submitted'){
                //     $query->where('status', 'like', trim($request->order_status));
                //     $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
                // } elseif(!empty($request->order_status) && trim($request->order_status) != 'submitted'){
                //     $query->where('status', 'like', trim($request->order_status));
                //     $query->where('warehouse_id', auth()->user()->warehouse_id);
                // }
            }]);
            $query->with(['productPoolDetails' => function($query){
                $query->with(["productPool" => function($query){
                    $query->with(["stockDetails" => function($query){
                        $query->where('warehouse_id', auth()->user()->warehouse_id);
                    }]);
                    // $query->whereNotNull('mp_order_confirmation_pending');
                }]);
            }]);
            $query->with('product');
            $query->where('vendor_id', Auth::user()->vendor_id);
            $query->where(function($query) {
                if(!empty(auth()->user()->warehouse_id)){
                    $query->where('warehouse_id', auth()->user()->warehouse_id);
                }
                $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
            });
        }])->where('type_of_sale', 1);

        if(!empty($request->start_date) && empty($request->end_date) ){
            $data->where('created_at', '>=', $request->start_date . ' 00:00:00');
        }
        if(!empty($request->end_date) && empty($request->start_date) ){
            $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        if(!empty($request->end_date) && !empty($request->start_date) ){
            $data->where('created_at', '>=', $request->start_date . ' 00:00:00');
            $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // if(!empty($request->order_from)){
        //     $data->where('type_of_sale', $request->order_from);
        // }
        if(!empty($request->invoice)){
            $data->where('invoice_no', trim($request->invoice));
        }
        $data = $data->get()->toArray();
        $pool = [];
        foreach($data as $sale) {
            if(empty($sale['sale_details'])){
                continue;
            }
            foreach($sale['sale_details'] as $saleDetail){
                if(empty($saleDetail['sale_order'])){
                    continue;
                }
                $qt = 0;
                foreach($saleDetail['product_pool_details']['product_pool']['stock_details'] as $psd){
                    $qt = $qt + $psd['available_quantity'];
                }
                if($qt <= 0){
                    continue;
                }
                $dt['stock'] = $qt;
                $dt['sale_detail_id'] = $saleDetail['id'];
                $dt['name'] = $saleDetail['product']['name'];
                $dt['attribute'] = $saleDetail['product_attributes_pair'];
                $dt['quantity'] = $saleDetail['quantity'];
                $dt['per_price'] = $saleDetail['per_price'];
                $dt['total_price'] = $saleDetail['total'];
                $dt['invoice'] = $sale['invoice_no'];
                $dt['status'] = $saleDetail['sale_order']['status'];
                $dt['placed_at'] = date('d-m-Y', strtotime($sale['created_at']));
                $dt['placed_from'] = $sale['type_of_sale'] == 1 ? 'POS' : 'Marketplace';
                $pool[] = $dt;
            }
        }
        return DataTables::of(collect($pool))
            ->addIndexColumn()
            ->editColumn('attribute', function ($pool) {
                return $pool['attribute'];
            })
            ->editColumn('status', function ($pool) {
                if($pool['status'] == 'submitted'){
                    return '<span href="#0" class="badge cursor-pointer badge-danger text-center">'.$pool['status'].'</span>';
                }
                elseif($pool['status'] == 'confirmed'){
                    return '<span href="#0" class="badge cursor-pointer badge-warning text-center">'.$pool['status'].'</span>';
                }
                elseif($pool['status'] == 'processed'){
                    return '<span href="#0" class="badge cursor-pointer badge-info text-center">'.$pool['status'].'</span>';
                }
                elseif($pool['status'] == 'shipped'){
                    return '<span href="#0" class="badge cursor-pointer badge-success text-center">'.$pool['status'].'</span>';
                } else {
                    return '<span href="#0" class="badge cursor-pointer badge-success text-center">'.$pool['status'].'</span>';
                }

            })
            ->editColumn('per_price', function ($pool) {
                return number_format($pool['per_price'], 2);

            })
            ->editColumn('total_price', function ($pool) {
                return number_format($pool['total_price'], 2);

            })
            ->addColumn('action', function ($pool) {
                if($pool['status'] == 'submitted'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-danger confirm">Confirm</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
                elseif($pool['status'] == 'confirmed'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-warning advance">Process</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
                elseif($pool['status'] == 'processed'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-info advance">Shipping</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
                elseif($pool['status'] == 'shipped'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-success advance">Deliver</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                } else {
                    return '<div class="btn-group"><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
            })
            ->rawColumns(['attribute','status','action', 'total_price', 'per_price'])
            ->make(true);
    }
    public function wholesaleOrder(Request $request) {
        if (!$request->ajax())
        {
            $title        = 'Sale Orders';
            $page_detail  = 'List of Sale Orders';
            // return view('sale_orders.wholesale_warehouse', compact('title', 'page_detail',));
            return view('sale_orders.wholesale_warehouse_without_barcode', compact('title', 'page_detail'));
        }

        $data = Sale::with(['saleDetails' => function($query) use ($request){
            $query->with(['saleOrder' => function($query) use ($request){
                if(!empty($request->order_status)){
                    $query->where('status', '=', trim($request->order_status));
                }
                // if(empty($request->order_status)){
                //     $query->where(function($query) {
                //         if(!empty(auth()->user()->warehouse_id)){
                //             $query->where('warehouse_id', auth()->user()->warehouse_id);
                //         }
                //         $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
                //     });
                // }
                // if(!empty($request->order_status) && trim($request->order_status) == 'submitted'){
                //     $query->where('status', 'like', trim($request->order_status));
                //     $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
                // } elseif(!empty($request->order_status) && trim($request->order_status) != 'submitted'){
                //     $query->where('status', 'like', trim($request->order_status));
                //     $query->where('warehouse_id', auth()->user()->warehouse_id);
                // }
            }]);
            $query->with(['productPoolDetails' => function($query){
                $query->with(["productPool" => function($query){
                    $query->with(["stockDetails" => function($query){
                        $query->where('warehouse_id', auth()->user()->warehouse_id);
                    }]);
                    // $query->whereNotNull('mp_order_confirmation_pending');
                }]);
            }]);
            $query->with('product');
            $query->where('vendor_id', Auth::user()->vendor_id);
            $query->where(function($query) {
                if(!empty(auth()->user()->warehouse_id)){
                    $query->where('warehouse_id', auth()->user()->warehouse_id);
                }
                $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
            });
        }])->where('type_of_sale', 2);

        if(!empty($request->start_date) && empty($request->end_date) ){
            $data->where('created_at', '>=', $request->start_date . ' 00:00:00');
        }
        if(!empty($request->end_date) && empty($request->start_date) ){
            $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        if(!empty($request->end_date) && !empty($request->start_date) ){
            $data->where('created_at', '>=', $request->start_date . ' 00:00:00');
            $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }

        // if(!empty($request->order_from)){
        //     $data->where('type_of_sale', $request->order_from);
        // }
        if(!empty($request->invoice)){
            $data->where('invoice_no', trim($request->invoice));
        }
        $data = $data->get()->toArray();
        $pool = [];
        foreach($data as $sale) {
            if(empty($sale['sale_details'])){
                continue;
            }
            foreach($sale['sale_details'] as $saleDetail){
                if(empty($saleDetail['sale_order'])){
                    continue;
                }
                $qt = 0;
                foreach($saleDetail['product_pool_details']['product_pool']['stock_details'] as $psd){
                    $qt = $qt + $psd['available_quantity'];
                }
                if($qt <= 0){
                    continue;
                }
                $dt['stock'] = $qt;
                $dt['sale_detail_id'] = $saleDetail['id'];
                $dt['name'] = $saleDetail['product']['name'];
                $dt['attribute'] = $saleDetail['product_attributes_pair'];
                $dt['quantity'] = $saleDetail['quantity'];
                $dt['per_price'] = $saleDetail['per_price'];
                $dt['total_price'] = $saleDetail['total'];
                $dt['invoice'] = $sale['invoice_no'];
                $dt['status'] = $saleDetail['sale_order']['status'];
                $dt['placed_at'] = date('d-m-Y', strtotime($sale['created_at']));
                $dt['placed_from'] = $sale['type_of_sale'] == 1 ? 'POS' : 'Marketplace';
                $pool[] = $dt;
            }
        }
        return DataTables::of(collect($pool))
            ->addIndexColumn()
            ->editColumn('attribute', function ($pool) {
                return $pool['attribute'];
            })
            ->editColumn('status', function ($pool) {
                if($pool['status'] == 'submitted'){
                    return '<span href="#0" class="badge cursor-pointer badge-danger text-center">'.$pool['status'].'</span>';
                }
                elseif($pool['status'] == 'confirmed'){
                    return '<span href="#0" class="badge cursor-pointer badge-warning text-center">'.$pool['status'].'</span>';
                }
                elseif($pool['status'] == 'processed'){
                    return '<span href="#0" class="badge cursor-pointer badge-info text-center">'.$pool['status'].'</span>';
                }
                elseif($pool['status'] == 'shipped'){
                    return '<span href="#0" class="badge cursor-pointer badge-success text-center">'.$pool['status'].'</span>';
                } else {
                    return '<span href="#0" class="badge cursor-pointer badge-success text-center">'.$pool['status'].'</span>';
                }

            })
            ->editColumn('per_price', function ($pool) {
                return number_format($pool['per_price'], 2);

            })
            ->editColumn('total_price', function ($pool) {
                return number_format($pool['total_price'], 2);

            })
            ->addColumn('action', function ($pool) {
                if($pool['status'] == 'submitted'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-danger confirm">Confirm</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
                elseif($pool['status'] == 'confirmed'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-warning advance">Process</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
                elseif($pool['status'] == 'processed'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-info advance">Shipping</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
                elseif($pool['status'] == 'shipped'){
                    return '<div class="btn-group"><a href="#" data-qty="'.$pool['quantity'].'"  data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-success advance">Deliver</a><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                } else {
                    return '<div class="btn-group"><a href="#" data-id="'.$pool['sale_detail_id'].'" class="btn btn-sm btn-primary detail">Detail</a></div>';
                }
            })
            ->rawColumns(['attribute','status','action', 'total_price', 'per_price'])
            ->make(true);
    }

    public function saleOrderAdminDetails(Request $request) {
        if (!$request->ajax())
        {
            $title        = 'Sale Orders';
            $page_detail  = 'List of Sale Orders';
            $warehouses  = Warehouse::where('vendor_id',Auth::user()->vendor_id)->pluck('name', 'id')->toArray();

            return view('sale_orders.pos_admin', compact('title', 'page_detail','warehouses'));
        }
        $data = Sale::with(['saleDetails' => function($query) use ($request){
            $query->with(['saleOrder' => function($query) use ($request){
                if(!empty($request->order_status)){
                    $query->where('status', '=', trim($request->order_status));
                    if(trim($request->order_status) == 'submitted'){
                        $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
                    }
                }
            }]);
            $query->with(['productPoolDetails' => function($query) use ($request){
                $query->with(["productPool" => function($query) use ($request){
                    $query->with(["stockDetails" => function($query) use ($request){
                        if(!empty($request->warehouse_id)){
                            $query->where('warehouse_id', $request->warehouse_id);
                        }
                    }]);
                }]);
            }]);
            $query->with('product');
            $query->with('warehouse');
            $query->where('vendor_id', Auth::user()->vendor_id);
        }]);

        if(!empty(request()->customer_id)){
            $customer_id = explode("_",$request->customer_id);
            if ($customer_id[0] == 'pos'){
                $data->where('pos_customer_id', $customer_id[1]);
            }elseif ($customer_id[0] == 'mp') {
                $data->where('marketplace_user_id', $customer_id[1]);
            }
        }

        if(!empty(request()->end_date) && !empty(request()->start_date)){
            $start_date = date('Y-m-d',strtotime(request()->start_date));
            $end_date = date('Y-m-d',strtotime(request()->end_date));
            $data->where('created_at', '>=', $start_date . ' 00:00:00');
            $data->where('created_at', '<=', $end_date . ' 23:59:59');
        }

        if(!empty($request->order_from)){
            $data->where('type_of_sale', $request->order_from);
        }
        if(!empty($request->invoice)){
            $data->where('invoice_no', trim($request->invoice));
        }
        if(!empty($request->warehouse_id)){
            $data->where('user_warehouse_id', $request->warehouse_id);
        }
        $data = $data->latest()->get()->toArray();
        $pool = [];
        foreach($data as $sale) {
            foreach($sale['sale_details'] as $saleDetail){
                if(empty($saleDetail['sale_order'])){
                    continue;
                }
                $dt['sale_id'] = $sale['id'];
                $dt['type_of_sale'] = $sale['type_of_sale'];
                $dt['pos_customer_id'] = $sale['pos_customer_id'];
                $dt['marketplace_user_id'] = $sale['marketplace_user_id'];
                $dt['sale_detail_id'] = $saleDetail['id'];
                $dt['warehouse'] = !empty($saleDetail['warehouse']) ? $saleDetail['warehouse']['name'] : 'N/A';
                $dt['name'] = $saleDetail['product']['name'];
                $dt['attribute'] = $saleDetail['product_attributes_pair'];
                $dt['quantity'] = $saleDetail['quantity'];
                $dt['per_price'] = $saleDetail['per_price'];
                $dt['total_price'] = $saleDetail['total'];
                $qt = 0;
                foreach($saleDetail['product_pool_details']['product_pool']['stock_details'] as $psd){
                    $qt = $qt + $psd['available_quantity'];
                }
                $dt['stock'] = $qt;
                $dt['total_payment'] = $sale['total_payment'];
                $dt['due_payment'] = $sale['due_payment'];
                $dt['invoice'] = $sale['invoice_no'];
                $dt['status'] = $saleDetail['sale_order']['status'];
                $dt['placed_at'] = date('d-m-Y', strtotime($sale['created_at']));
                $dt['placed_from'] = $sale['type_of_sale'] == 1 ? 'POS' : 'Marketplace';
                $pool[] = $dt;
            }
        }
        return DataTables::of(collect($pool))
            ->addIndexColumn()
            ->editColumn('attribute', function ($pool) {
                return $pool['attribute'];
            })
            ->editColumn('status', function ($pool) {
                if($pool['status'] == 'submitted'){
                    if ($pool['total_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-danger text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-danger text-center">Not Paid</span>';
                    }elseif ($pool['due_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-danger text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-success text-center">Paid</span>';
                    }else{
                        return '<span href="#0" class="badge cursor-pointer badge-danger text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-warning text-center">Partial Paid</span>';
                    }
                }
                elseif($pool['status'] == 'confirmed'){
                    if ($pool['total_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-warning text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-danger text-center">Not Paid</span>';
                    }elseif ($pool['due_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-warning text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-success text-center">Paid</span>';
                    }else{
                        return '<span href="#0" class="badge cursor-pointer badge-warning text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-warning text-center">Partial Paid</span>';
                    }
                }
                elseif($pool['status'] == 'processed'){
                    if ($pool['total_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-info text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-danger text-center">Not Paid</span>';
                    }elseif ($pool['due_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-info text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-success text-center">Paid</span>';
                    }else{
                        return '<span href="#0" class="badge cursor-pointer badge-info text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-warning text-center">Partial Paid</span>';
                    }
                }
                elseif($pool['status'] == 'shipped'){
                    if ($pool['total_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-danger text-center">Not Paid</span>';
                    }elseif ($pool['due_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-success text-center">Paid</span>';
                    }else{
                        return '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-warning text-center">Partial Paid</span>';
                    }
                } else {
                    if ($pool['total_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-danger text-center">Not Paid</span>';
                    }elseif ($pool['due_payment'] == 0) {
                        return '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>
                                <span href="#0" style="background-color: #26C281" class="badge cursor-pointer badge-success text-center">Paid</span>';
                    }else{
                        return '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>
                                <span href="#0" class="badge cursor-pointer badge-warning text-center">Partial Paid</span>';
                    }
                }
            })
            ->editColumn('per_price', function ($pool) {
                return number_format($pool['per_price'], 2);

            })
            ->editColumn('total_price', function ($pool) {
                return number_format($pool['total_price'], 2);

            })
            ->addColumn('action', function ($pool) {
                if ($pool['due_payment'] != 0 && $pool['type_of_sale'] == 1) {
                    return '<div class="btn-group"><a href="#" data-id="' . $pool['sale_detail_id'] . '" class="btn btn-sm btn-info detail"><i class="fa fa-eye"></i></a></div>
                        <div class="btn-group"><a href="#" data-id="' . $pool['sale_detail_id'] . '" class="btn btn-sm btn-primary invoice">Invoice</a></div>
                        <div class="btn-group"><a href="'.url('/admin/sell/order/chalan/').'/'.$pool['sale_id'].'" target="_blank" class="btn btn-sm btn-secondary chalan">Chalan</a></div>
                        <div class="btn-group"><a href="'.url('/admin/add/pos_customer_payment/').'/'.$pool['pos_customer_id'].'?sale_id='.$pool['sale_id'].'" class="btn btn-sm btn-danger" target="_blank">Pay</a></div>';
                }elseif ($pool['due_payment'] != 0 && $pool['type_of_sale'] == 2) {
                    return '<div class="btn-group"><a href="#" data-id="' . $pool['sale_detail_id'] . '" class="btn btn-sm btn-info detail"><i class="fa fa-eye"></i></a></div>
                        <div class="btn-group"><a href="#" data-id="' . $pool['sale_detail_id'] . '" class="btn btn-sm btn-primary invoice">Invoice</a></div>
                        <div class="btn-group"><a href="'.url('/admin/sell/order/chalan/').'/'.$pool['sale_id'].'" target="_blank" class="btn btn-sm btn-secondary chalan">Chalan</a></div>
                        <div class="btn-group"><a href="'.url('/admin/add/mp_customer_payment/').'/'.$pool['marketplace_user_id'].'?sale_id='.$pool['sale_id'].'" class="btn btn-sm btn-danger" target="_blank">Pay</a></div>';
                }else{
                    return '<div class="btn-group"><a href="#" data-id="' . $pool['sale_detail_id'] . '" class="btn btn-sm btn-info detail"><i class="fa fa-eye"></i></a></div>
                        <div class="btn-group"><a href="#" data-id="' . $pool['sale_detail_id'] . '" class="btn btn-sm btn-primary invoice">Invoice</a></div>
                        <div class="btn-group"><a href="'.url('/admin/sell/order/chalan/').'/'.$pool['sale_id'].'" target="_blank" class="btn btn-sm btn-secondary chalan">Chalan</a></div>';
                }

            })
            ->rawColumns(['attribute','status','action', 'total_price', 'per_price'])
            ->make(true);
    }

    public function invoiceList(Request $request) {
        if($request->ajax()){
            $saleDetail = SaleDetail::with(['sale' => function($query) use ($request){
                if(!empty(trim($request->search))){
                    $query->where('invoice_no', 'like', '%'.trim($request->search).'%');
                }
            }]);
            if(!empty(auth()->user()->vendor_id)){
                $saleDetail->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty(auth()->user()->warehouse_id)){
                $saleDetail->where(function($query) {
                    if(!empty(auth()->user()->warehouse_id)){
                        $query->where('warehouse_id', auth()->user()->warehouse_id);
                    }
                    $query->orWhereRaw('COALESCE(warehouse_id, 0) = 0');
                });
            }
            $saleDetail = $saleDetail->get();
            $invoices = [];
            foreach($saleDetail as $sd){
                if(!empty($sd->sale)){
                    $invoices[$sd->sale->invoice_no] = $sd->sale->invoice_no;
                } else {
                    continue;
                }
            }
            return response()->json($invoices, Response::HTTP_OK);
        }
    }

    public function updateSaleOrderStatus(Request $request) {

        DB::beginTransaction();
        try{
            $stockedBarcode = StockedProductBarcode::with(["productPoolStockDetail" => function($query){
                $query->with(["productPool"]);
            }])->where('sale_detail_id', $request->sale_detail_id)->get();
            $saleDetail = SaleDetail::with(['saleOrder'])->with(['sale'])->where('id', $request->sale_detail_id)->first();
            $sale = $saleDetail->sale;
            $saleOrder = $saleDetail->saleOrder;
            if($saleOrder->status == 'delivered'){
                return response()->json(['code' => 0, 'success'=> false,'msg' => 'Nothing To Do Now!!']);
            }

            if($saleOrder->status == 'confirmed'){
                $saleOrder->status = 'processed';
                $saleOrder->processed_at = Carbon::now();

                $saleDetail->status = 'processed';

                $sale->status = 'processed';
                $sale->delivery_status = 'processed';
                $sale->updated_at = Carbon::now();
                $sale->updated_by = Auth::id();
                $sale->save();

                foreach($stockedBarcode as $barcode){
                    $barcode->productPoolStockDetail->mp_order_processed_quantity =
                        !empty($barcode->productPoolStockDetail->mp_order_processed_quantity)
                            ? intval($barcode->productPoolStockDetail->mp_order_processed_quantity) + 1 : 1;
                    $barcode->productPoolStockDetail->save();


                    $barcode->productPoolStockDetail->productPool->mp_order_processed_quantity =
                    !empty($barcode->productPoolStockDetail->productPool->mp_order_processed_quantity)
                    ? intval($barcode->productPoolStockDetail->productPool->mp_order_processed_quantity) + 1 : 1;
                    $barcode->productPoolStockDetail->productPool->updated_at = Carbon::now();
                    $barcode->productPoolStockDetail->productPool->updated_by = auth()->user()->id;
                    $barcode->productPoolStockDetail->productPool->save();
                }
            } elseif($saleOrder->status == 'processed'){
                $saleOrder->status = 'shipped';
                $saleOrder->shipped_at = Carbon::now();

                $saleDetail->status = 'shipped';

                $sale->status = 'shipped';
                $sale->delivery_status = 'shipped';
                $sale->updated_at = Carbon::now();
                $sale->updated_by = Auth::id();
                $sale->save();

                foreach($stockedBarcode as $barcode){
                    $barcode->productPoolStockDetail->mp_order_shipped_quantity =
                        !empty($barcode->productPoolStockDetail->mp_order_shipped_quantity)
                            ? intval($barcode->productPoolStockDetail->mp_order_shipped_quantity) + 1 : 1;
                    $barcode->productPoolStockDetail->save();


                    $barcode->productPoolStockDetail->productPool->mp_order_shipped_quantity =
                    !empty($barcode->productPoolStockDetail->productPool->mp_order_shipped_quantity)
                    ? intval($barcode->productPoolStockDetail->productPool->mp_order_shipped_quantity) + 1 : 1;
                    $barcode->productPoolStockDetail->productPool->updated_at = Carbon::now();
                    $barcode->productPoolStockDetail->productPool->updated_by = auth()->user()->id;
                    $barcode->productPoolStockDetail->productPool->save();
                }
            } elseif($saleOrder->status == 'shipped'){
                $saleOrder->status = 'delivered';
                $saleOrder->delivered_at = Carbon::now();

                $saleDetail->status = 'delivered';

                $sale->status = 'delivered';
                $sale->delivery_status = 'delivered';
                $sale->updated_at = Carbon::now();
                $sale->updated_by = Auth::id();
                $sale->save();

                foreach($stockedBarcode as $barcode){
                    $barcode->productPoolStockDetail->mp_order_delivered_quantity =
                        !empty($barcode->productPoolStockDetail->mp_order_delivered_quantity)
                            ? intval($barcode->productPoolStockDetail->mp_order_delivered_quantity) + 1 : 1;
                    $barcode->productPoolStockDetail->save();


                    $barcode->productPoolStockDetail->productPool->mp_order_delivered_quantity =
                    !empty($barcode->productPoolStockDetail->productPool->mp_order_delivered_quantity)
                    ? intval($barcode->productPoolStockDetail->productPool->mp_order_delivered_quantity) + 1 : 1;
                    $barcode->productPoolStockDetail->productPool->save();
                }
            }
            $saleOrder->warehouse_id = auth()->user()->warehouse_id;
            $saleOrder->updated_by = Auth::id();
            $saleOrder->updated_at = Carbon::now();
            $saleOrder->save();

            $saleDetail->warehouse_id =  auth()->user()->warehouse_id;
            $saleDetail->save();
            DB::commit();
            return response()->json(['code' => 1, 'success'=> true, 'msg' => 'Successfully ' . $saleOrder->status]);
        } catch (\Exception $exc) {
            DB::rollback();
            return response()->json(['code' => 0, 'success'=> false, 'raw'=>$exc->getMessage(), 'msg' =>'Something went wrong !!' ]);
        }
    }
    public function confirmSaleOrderStatus(Request $request) {
        DB::beginTransaction();
        try{
            $barcode = $request->barcode;
            $saleDetailId = $request->sale_detail_id;
            if(!empty(SaleDetail::where('id', $saleDetailId)->pluck('warehouse_id')->first())){
                return response()->json(['code' => 0, 'success'=> false, 'msg' =>'Order already confirmed by different warehouse' ]);
            }
            // $stockDetailIds = [];
            foreach($barcode as $k=>$code){
                $stockedBarcode = StockedProductBarcode::with(['productStockDetailFromBarcode' => function($query){
                    $query->with(["productPoolDetails" => function($query){
                        $query->with("productPool");
                    }]);
                }])
                ->where('bar_code', trim($code))->first();

                $stockedBarcode->sale_detail_id = $saleDetailId;
                $stockedBarcode->updated_at = Carbon::now();
                $stockedBarcode->save();

                $poolStock = $stockedBarcode->productStockDetailFromBarcode->productPoolDetails;
                $poolStock->mp_order_confirmed_quantity = !empty($poolStock->mp_order_confirmed_quantity) ? intval($poolStock->mp_order_confirmed_quantity) + 1 : 1;
                $poolStock->save();

                $pool = $stockedBarcode->productStockDetailFromBarcode->productPoolDetails->productPool;
                $pool->mp_order_confirmed_quantity = !empty($pool->mp_order_confirmed_quantity) ? intval($pool->mp_order_confirmed_quantity) + 1 : 1;
                $pool->mp_order_confirmation_pending = !empty($pool->mp_order_confirmation_pending) ? intval($pool->mp_order_confirmation_pending) - 1 : 0;
                $pool->save();

                // update stock status in original stock detail, product pool, product pool stock detail
                if($poolStock->available_quantity <= 0){
                    $poolStock->stock_status = 'stock_out';
                    $poolStock->save();

                    $stockDetail = $stockedBarcode->productStockDetailFromBarcode;
                    $stockDetail->status = 'stock_out';
                    $stockDetail->save();
                }

                if($pool->available_quantity <= 0){
                    $pool->stock_status = 'stock_out';
                    $pool->save();
                }
                // $stockDetailIds[$stockedBarcode->stock_detail_id] = $stockedBarcode->stock_detail_id;
            }

            $saleDetail = SaleDetail::where('id', $saleDetailId)->with('saleOrder')->with('sale')->first();
            $saleDetail->status = 'confirmed';
            $saleDetail->warehouse_id = auth()->user()->warehouse_id;
            $saleDetail->updated_at = Carbon::now();
            $saleDetail->save();

            $saleOrder = $saleDetail->saleOrder;
            $saleOrder->warehouse_id = auth()->user()->warehouse_id;
            $saleOrder->status = 'confirmed';
            $saleOrder->confirmed_at = Carbon::now();
            $saleOrder->updated_at = Carbon::now();
            $saleOrder->updated_by = Auth::id();
            $saleOrder->save();

            $sale = $saleDetail->sale;
            $sale->status = 'confirmed';
            $sale->delivery_status = 'confirmed';
            $sale->updated_at = Carbon::now();
            $sale->updated_by = Auth::id();
            $sale->save();

        } catch (\Exception $exc) {
            DB::rollback();
            return response()->json(['code' => 0, 'success'=> false, 'raw'=>$exc->getMessage(), 'msg' =>'Something went wrong !!' ]);
        }
        DB::commit();
        return response()->json(['code' => 1, 'success'=> true, 'msg' => 'Successfully ' . $saleOrder->status]);
    }
    public function confirmSaleOrderStatusWithoutBarcode(Request $request) {
        DB::beginTransaction();
        try{
            $poolIds = ProductPoolSaleDetail::where('sale_detail_id', $request->sale_detail_id)->pluck('product_pool_id');
            $stockDetailIds = ProductPoolStockDetail::whereIn('product_pool_id', $poolIds)->where('warehouse_id', auth()->user()->warehouse_id)->pluck('stock_detail_id');
            $barcode = StockedProductBarcode::whereIn('stock_detail_id', $stockDetailIds)->whereNull('sale_detail_id')->limit(intval($request->quantity))->oldest()->pluck('bar_code');
            $saleDetailId = $request->sale_detail_id;
            if(count($barcode) < intval($request->quantity)){
                return response()->json(['code' => 0, 'success'=> false, 'msg' =>'Stock Unavailable' ]);
            }
            if(!empty(SaleDetail::where('id', $saleDetailId)->pluck('warehouse_id')->first())){
                return response()->json(['code' => 0, 'success'=> false, 'msg' =>'Order already confirmed by different warehouse' ]);
            }
            foreach($barcode as $k=>$code){
                $stockedBarcode = StockedProductBarcode::with(['productStockDetailFromBarcode' => function($query){
                    $query->with(["productPoolDetails" => function($query){
                        $query->with("productPool");
                    }]);
                }])
                ->where('bar_code', trim($code))->first();

                $stockedBarcode->sale_detail_id = $saleDetailId;
                $stockedBarcode->updated_at = Carbon::now();
                $stockedBarcode->save();

                $poolStock = $stockedBarcode->productStockDetailFromBarcode->productPoolDetails;
                $poolStock->mp_order_confirmed_quantity = !empty($poolStock->mp_order_confirmed_quantity) ? intval($poolStock->mp_order_confirmed_quantity) + 1 : 1;
                $poolStock->save();

                $pool = $stockedBarcode->productStockDetailFromBarcode->productPoolDetails->productPool;
                $pool->mp_order_confirmed_quantity = !empty($pool->mp_order_confirmed_quantity) ? intval($pool->mp_order_confirmed_quantity) + 1 : 1;
                $pool->mp_order_confirmation_pending = !empty($pool->mp_order_confirmation_pending) ? intval($pool->mp_order_confirmation_pending) - 1 : 0;
                $pool->save();

                // update stock status in original stock detail, product pool, product pool stock detail
                if($poolStock->available_quantity <= 0){
                    $poolStock->stock_status = 'stock_out';
                    $poolStock->save();

                    $stockDetail = $stockedBarcode->productStockDetailFromBarcode;
                    $stockDetail->status = 'stock_out';
                    $stockDetail->save();
                }

                if($pool->available_quantity <= 0){
                    $pool->stock_status = 'stock_out';
                    $pool->save();
                }
                // $stockDetailIds[$stockedBarcode->stock_detail_id] = $stockedBarcode->stock_detail_id;
            }

            $saleDetail = SaleDetail::where('id', $saleDetailId)->with('saleOrder')->with('sale')->first();

            /// ===>> Start Profit && Loss
            $total_quantity = SaleDetail::where('sale_id',$saleDetail->sale->id)->sum('quantity');
            //$total_quantity = array_sum($quantity_arr);
            $per_discount = ($saleDetail->sale->discount / $total_quantity);
            $product_data = Product::where('id',$saleDetail->product_id)->where('vendor_id',$saleDetail->vendor_id)->first();
            $saleDetail->average_purchase_price = $product_data->average_purchase_price;
            $saleDetail->total_cumulative_purchase_price = ($saleDetail->quantity * $product_data->average_purchase_price);
            $saleDetail->total_cumulative_sold_price = ($saleDetail->quantity * ($saleDetail->per_price - $per_discount));
            $saleDetail->total_cumulative_profit = ($saleDetail->total_cumulative_sold_price - $saleDetail->total_cumulative_purchase_price);
            /// ===>> End Profit Loss

            $saleDetail->status = 'confirmed';
            $saleDetail->warehouse_id = auth()->user()->warehouse_id;
            $saleDetail->updated_at = Carbon::now();
            $saleDetail->save();

            $saleOrder = $saleDetail->saleOrder;
            $saleOrder->warehouse_id = auth()->user()->warehouse_id;
            $saleOrder->status = 'confirmed';
            $saleOrder->confirmed_at = Carbon::now();
            $saleOrder->updated_at = Carbon::now();
            $saleOrder->updated_by = Auth::id();
            $saleOrder->save();

            $sale = $saleDetail->sale;

            /// ===>> START SuperAdmin Vendor Sale Commission
            $vC = auth()->user()->vendor->active_sale_commission;
            $swg = $sale->final_total * ($vC/100);
            $vwg = $sale->final_total - $swg;
            $sale->commission_percentage = $vC;
            $sale->superadmin_will_get = $swg;
            $sale->vendor_will_get = $vwg;
            $sale->user_warehouse_id = auth()->user()->warehouse_id;
            /// ===>> END SuperAdmin Vendor Sale Commission

            $sale->status = 'confirmed';
            $sale->delivery_status = 'confirmed';
            $sale->updated_at = Carbon::now();
            $sale->updated_by = Auth::id();
            $sale->save();

        } catch (\Exception $exc) {
            DB::rollback();
            return response()->json(['code' => 0, 'success'=> false, 'raw'=>$exc->getMessage(), 'msg' =>'Something went wrong !!' ]);
        }
        DB::commit();
        return response()->json(['code' => 1, 'success'=> true, 'msg' => 'Successfully ' . $saleOrder->status]);
    }

    public function stockBarcodeCheck(Request $request){
        $poolIds = ProductPoolSaleDetail::where('sale_detail_id', $request->sale_detail_id)->pluck('product_pool_id');
        $stockDetailIds = ProductPoolStockDetail::whereIn('product_pool_id', $poolIds)->pluck('stock_detail_id');
        $barcode = StockedProductBarcode::where('bar_code', trim($request->barcode))->whereIn('stock_detail_id', $stockDetailIds)->whereNull('sale_detail_id')->get();
        if($barcode->first()){
            return response()->json(['code' => 1, 'success'=> true, 'data'=> $barcode->first()]);
        } else {
            return response()->json(['code' => 0, 'success'=> false]);
        }
    }

    public function saleOrderDetail(Request $request) {
        $title        = 'Stock Overview';
        $page_detail  = 'Stock overview for submitted order';
        $saleDetailId = $request->sale_detail_id;
        $saleDetail = SaleDetail::where('id', $saleDetailId);
        if(!empty($saleDetail->pluck('warehouse_id')->first())){
            $saleDetail = $saleDetail->with('sale')
                    ->with('saleOrder')
                    ->with(['productPoolDetails' => function($query){
                        $query->with(["productPool"]);
                    }])
                    ->with(['product'])
                    ->with(['soldBarcode' => function($query){
                        $query->with(["productPoolStockDetail"=> function($query){
                            $query->with("warehouse");
                            $query->with("warehouseDetail");
                        }]);
                    }])
                    ->where('id', $saleDetailId)
                    ->first()->toArray();

            $saleOrder = $saleDetail['sale_order'];
            $pool = $saleDetail['product_pool_details']['product_pool'];
            $product = $saleDetail['product'];
            $saleAttribute = $saleDetail['sale_attribute_details'];
            $singleSale = $saleDetail['sale'];
            $barcodes = $saleDetail['sold_barcode'];
            return view('sale_orders.single_order', compact('singleSale', 'saleOrder', 'pool', 'barcodes', 'product', 'saleAttribute', 'saleDetail', 'title', 'page_detail'));
        } else {
            $saleDetail = $saleDetail->with('sale')
                    ->with('saleOrder')
                    ->with(['productPoolDetails' => function($query){
                        $query->with(["productPool" => function($query){
                            $query->with(["stockDetails" => function($query){
                                if(!empty(auth()->user()->warehouse_id)){
                                    $query->where('warehouse_id', auth()->user()->warehouse_id);
                                }
                                $query->with("warehouse");
                                $query->with("warehouseDetail");
                            }]);
                        }]);
                    }])
                    ->with(['product'])
                    ->where('id', $saleDetailId)
                    ->first()->toArray();
            $saleOrder = $saleDetail['sale_order'];
            $pool = $saleDetail['product_pool_details']['product_pool'];
            $poolStockDetail = $saleDetail['product_pool_details']['product_pool']['stock_details'];
            $product = $saleDetail['product'];
            $saleAttribute = $saleDetail['sale_attribute_details'];
            $singleSale = $saleDetail['sale'];
            return view('sale_orders.single_submitted_order', compact('singleSale', 'saleOrder', 'pool', 'poolStockDetail', 'product', 'saleAttribute', 'saleDetail', 'title', 'page_detail'));
        }
    }
    public function saleOrderDetailInvoice(Request $request) {
        $title        = 'Stock Overview';
        $page_detail  = 'Stock overview for submitted order';
        $saleDetailId = $request->sale_detail_id;
        $saleDetail = SaleDetail::where('id', $saleDetailId)->first();

        $sale = Sale::where('id', $saleDetail->sale_id)->with(['saleDetails'=> function ($query){
                                                                    $query->with('product');
                                                                }])->first();
        $user_address = array();
        if ($sale->marketplace_user_id != 0){
            if ($sale->marketplace_user_address_id != null){
                $user_address = MarketplaceUserAddress::find($sale->marketplace_user_address_id);
            }else{
                $user_addresss = MarketplaceUserAddress::where('marketplace_user_id',$sale->marketplace_user_id)->get();
                if (!empty($user_addresss)) {
                    foreach ($user_addresss as $key => $value) {
                        if ($value->is_default_address == 1) {
                            $user_address = $value;
                            break;
                        } else {
                            $user_address = $value;
                        }
                    }
                }
            }
        }
        $saleDetails = $sale['saleDetails'];
        $sales= json_decode($sale,true);
        $image = Auth::user()->image;

        return view('sale_orders.invoice_generator', compact('image','user_address','sales', 'saleDetails', 'title', 'page_detail'));
    }
}
