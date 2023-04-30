<?php

namespace App\Http\Controllers;


use App\MarketplaceUser;
use App\ProductPool;
use App\Product;
use App\Sale;
use App\SalePayment;
use App\StockDetail;
use Illuminate\Http\Request;
use App\ProductReturn;
use App\SaleDetail;
use App\SaleAttributeDetails;
use App\ProductAttribute;
use App\ProductAttributeMap;
use App\ProductPoolProductReturn;
use App\ProductPoolSaleDetail;
use App\SaleOrder;
use App\StockedProductBarcode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use DataTables;

class RetailSellController extends Controller {

    public function index() {
//        if (strtolower(auth()->user()->user_role->name) != 'pos') {
//            if (!$this->current_warehouse_id) {
//                return redirect('/')->with('error', 'Warehouse not found');
//            }
//        }
        return view('retail_sell.index');
    }

    public function products(Request $request) {
        $without_barcode = explode(',', $request->without_barcode);
        $search_key = $request->search_key;
        $category_id = $request->category_id;
        $brand_id = $request->brand_id;
        $products = StockDetail::join('products', 'products.id', '=', 'stock_details.product_id')
            ->join('product_pool_stock_details', 'product_pool_stock_details.stock_detail_id', '=', 'stock_details.id')
            ->join('product_pools', 'product_pools.id', '=', 'product_pool_stock_details.product_pool_id')
            ->with('productPoolDetails')
            ->where('products.status', 1)
            ->select('stock_details.*',
            'product_pool_stock_details.id as ppsd_id',
            'product_pool_stock_details.stock_detail_id as ppsd_stock_detail_id',
            'product_pool_stock_details.product_pool_id  as ppsd_product_pool_id',
            'product_pool_stock_details.warehouse_id  as ppsd_warehouse_id',
            'product_pool_stock_details.warehouse_detail_id as ppsd_warehouse_detail_id',
            'product_pool_stock_details.stock_quantity as ppsd_stock_quantity',
            'product_pool_stock_details.pos_order_submitted_quantity as ppsd_pos_order_submitted_quantity',
            'product_pool_stock_details.mp_order_confirmed_quantity as ppsd_mp_order_confirmed_quantity',
            'product_pool_stock_details.stock_transfer_quantity as ppsd_stock_transfer_quantity',
            'product_pool_stock_details.exchange_quantity as ppsd_exchange_quantity',
            'product_pools.id as pool_id',
            'product_pools.product_id as pool_product_id',
            'product_pools.stock_quantity as pool_stock_quantity',
            'product_pools.pos_order_submitted_quantity as pool_pos_order_submitted_quantity',
            'product_pools.pos_order_confirmed_quantity as pool_pos_order_confirmed_quantity',
            'product_pools.pos_order_processed_quantity as pool_pos_order_processed_quantity',
            'product_pools.pos_order_shipped_quantity as pool_pos_order_shipped_quantity',
            'product_pools.pos_order_delivered_quantity as pool_pos_order_delivered_quantity',
            'product_pools.mp_order_submitted_quantity as pool_mp_order_submitted_quantity',
            'product_pools.mp_order_confirmation_pending as pool_mp_order_confirmation_pending',
            'product_pools.mp_order_confirmed_quantity as pool_mp_order_confirmed_quantity',
            'product_pools.mp_order_processed_quantity as pool_mp_order_processed_quantity',
            'product_pools.mp_order_shipped_quantity as pool_mp_order_shipped_quantity',
            'product_pools.mp_order_delivered_quantity as pool_mp_order_delivered_quantity',
            'product_pools.return_quantity as pool_return_quantity',
            'product_pools.exchange_quantity as pool_exchange_quantity',
            'product_pools.stock_status as pool_stock_status'
            )
            ->where('products.vendor_id', auth()->user()->vendor_id)
//            ->where('stock_details.warehouse_id', $this->current_warehouse_id)
            ->where(function($query) use($search_key) {
                $query->where('products.name', 'like', '%'.$search_key.'%')
                    ->orWhereHas('productBarcodesFromStockDetail', function($query2) use($search_key) {
                        $query2->where('bar_code', 'like', '%'.$search_key.'%');
                    });
            });
        if (!empty($category_id)){
            $products = $products->where(function($query) use($category_id) {
                $query->where('products.product_category_id', $category_id);
            });
        }
        if (!empty($brand_id)){
            $products = $products->where(function($query) use($brand_id) {
                $query->where('products.product_brand_id', $brand_id);
            });
        }
        $products = $products->inRandomOrder()
                            // ->where('stock_details.quantity', '>', 0)
                            ->whereRaw('(product_pool_stock_details.stock_quantity - COALESCE(product_pool_stock_details.stock_transfer_quantity ,0) - COALESCE(product_pool_stock_details.mp_order_confirmed_quantity ,0) - COALESCE(product_pool_stock_details.pos_order_submitted_quantity,0) - COALESCE(product_pool_stock_details.exchange_quantity,0)) > ?', [0])
                            ->get()
                            ->take(35);
        return view('retail_sell.products', compact('products', 'without_barcode', 'search_key'));
    }

    public function receipt($sale_id) {
        $saleData = Sale::find($sale_id);
        return view('retail_sell.receipt', compact('saleData'));
    }

    public function todaySales() {
        $sales = Sale::whereDate('created_at', date('Y-m-d'))->get();
        return view('retail_sell.today_sales', compact('sales'));
    }

    public function payOption($sale_id) {
        $saleData = Sale::find($sale_id);
        return view('retail_sell.payment', compact('saleData'));
    }

    public function dueOrders() {
        return view('retail_sell.due_orders');
    }

    public function dueOrdersListByAjax(Request $request){
        if (!$request->ajax())
        {
            abort(404);
        }
        $sales = Sale::get()
            ->where('due_payment', '>', 0);
        return Datatables::of($sales)
            ->addIndexColumn()

            ->editColumn('name', function ($sales) {
                if(!empty($sales->posCustomer)){
                    return $sales->posCustomer->name;
                }
                else{
                    return $sales->marketplaceUser->name;
                }
            })
            ->editColumn('final_total', function ($sales) {
               return number_format($sales->final_total, 2);
            })
            ->editColumn('total_payment', function ($sales) {
                return number_format($sales->total_payment, 2);
            })
            ->editColumn('due_payment', function ($sales) {
                return number_format($sales->due_payment, 2);
            })
            ->addColumn('action', function ($sales) {
                return '<div class="btn-group">
                        <button class="btn btn-sm btn-success btnPaymentDue" data-id="'.$sales->id.'">Pay</button>
                        </div>';
            })
            ->rawColumns(['name','final_total','total_payment','due_payment','action'])
            ->make(true);
    }

    public function pendingReturnRequest(Request $request) {
        $product_return = ProductReturn::with(['product'])->where(['vendor_id' => Auth::user()->vendor_id, 'status' => 'requested'])->get();
        return view('retail_sell.pending_return_request', compact('product_return'));
    }
    public function returnRequest(Request $request) {
        return view('retail_sell.return_request');
    }

    public function exchangeProductDetail(Request $request) {
        $productDetail = [];
        $barcode_product = StockedProductBarcode::with(['productStockDetailFromBarcode' => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
//                $query->where('warehouse_id', Auth::user()->warehouse_id);
            }])
            ->with(['productFromBarcode' => function ($query) {
                $query->with('productCategory');
                $query->where('vendor_id', Auth::user()->vendor_id);
            }])
            ->with(['purchaseDetail' => function($query) {
                $query->with('purchaseAttributeDetails');
            }])
            ->where('bar_code', trim($request->barcode))
            ->first();

        $stock_detail = $barcode_product->productStockDetailFromBarcode;
        $product = $barcode_product->productFromBarcode;
        $product_attribute = $barcode_product->purchaseDetail->attributeDetail->all();
        return view('retail_sell.exchange_product_detail', compact('barcode_product', 'stock_detail', 'product','product_attribute'));

    }
    public function returnRequestDetail(Request $request) {
        if(!$request->has('return_product_id') || empty($request->return_product_id)){
            $from_return_request = false;
            $product_return_detail = [];
            $product_return_detail = StockedProductBarcode::with(['saleDetail' => function ($query) {
                    $query->with(['sale' => function($query) {
                        $query->with('payment');
                    }]);
                    $query->where('vendor_id', Auth::user()->vendor_id);
                }])
                ->with(['productStockDetailFromBarcode' => function ($query) {
                    $query->where('vendor_id', Auth::user()->vendor_id);
//                    $query->where('warehouse_id', Auth::user()->warehouse_id);
                }])
                ->with(['productFromBarcode' => function ($query) {
                    $query->with('productCategory');
                    $query->where('vendor_id', Auth::user()->vendor_id);
                }])
                ->with(['purchaseDetail' => function($query) {
                    $query->with('purchaseAttributeDetails');
                }])
                ->where('bar_code', trim($request->return_product_barcode))
                ->first();

            $return_barcode = $product_return_detail;
            $return_sale_detail = $product_return_detail->saleDetail;
            $return_sale = $product_return_detail->saleDetail->sale;
            $return_sale_payment = $product_return_detail->saleDetail->sale->payment->all();
            $return_stock_detail = $product_return_detail->productStockDetailFromBarcode;
            $return_product = $product_return_detail->productFromBarcode;
            $return_product_attribute = $product_return_detail->purchaseDetail->attributeDetail->all();
            return view('retail_sell.request_detail', compact('return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute','from_return_request'));
        } else {
            $from_return_request = true;
            $product_return_detail = [];
            $product_return_detail = ProductReturn::with(['returnedProductBarcode' => function ($query) {
                    $query->with(['saleDetail' => function ($query) {
                        $query->with(['sale' => function($query) {
                            $query->with('payment');
                        }]);
                        $query->where('vendor_id', Auth::user()->vendor_id);
                    }]);
                    $query->with(['productStockDetailFromBarcode' => function ($query) {
                        $query->where('vendor_id', Auth::user()->vendor_id);
//                        $query->where('warehouse_id', Auth::user()->warehouse_id);
                    }]);
                    $query->with(['productFromBarcode' => function ($query) {
                        $query->where('vendor_id', Auth::user()->vendor_id);
                    }]);
                    $query->with(['purchaseDetail' => function($query) {
                        $query->with('attributeDetail');
                    }]);
                }])
                ->where('id', $request->return_product_id)
                ->first();
            $return_detail = $product_return_detail;
            $return_barcode = $product_return_detail->returnedProductBarcode;

            $return_sale_detail = $product_return_detail->returnedProductBarcode->saleDetail;
            $return_sale = $product_return_detail->returnedProductBarcode->saleDetail->sale;
            $return_sale_payment = $product_return_detail->returnedProductBarcode->saleDetail->sale->payment->all();
            $return_stock_detail = $product_return_detail->returnedProductBarcode->productStockDetailFromBarcode;
            $return_product = $product_return_detail->returnedProductBarcode->productFromBarcode;
            $return_product_attribute = $product_return_detail->returnedProductBarcode->purchaseDetail->attributeDetail->all();
            return view('retail_sell.request_detail', compact('return_detail','return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute','from_return_request'));
        }
    }

    public function returnRequestSubmit(Request $request) {
        $sold_stocked_product_barcode = StockedProductBarcode::has('productStockDetailFromBarcode')
            ->has('productFromBarcode')
            ->has('saleDetail')
            ->with(['saleDetail' => function ($query) {
                $query->with(['productPoolDetails' => function($query){
                    $query->with('productPool');
                }]);
                $query->where('vendor_id', Auth::user()->vendor_id);
            }])
            ->with(['productStockDetailFromBarcode' => function ($query) {
                $query->with(['productPoolDetails' => function($query){
//                    $query->where('warehouse_id', Auth::user()->warehouse_id);
                }]);
                $query->where('vendor_id', Auth::user()->vendor_id);
//                $query->where('warehouse_id', Auth::user()->warehouse_id);
            }])
            ->with(['productFromBarcode' => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
            }])
            ->where('bar_code', $request->barcode)
            ->where('sale_detail_id', '!=', 0)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->first();

        DB::beginTransaction();
        try {
            if ($sold_stocked_product_barcode) {
                if(!empty($request->id)){
                    $productReturn = ProductReturn::find($request->id);
                    $productReturn->product_id = $sold_stocked_product_barcode->product_id;
                    $productReturn->returned_stocked_product_barcode = trim($sold_stocked_product_barcode->bar_code);
                    $productReturn->returned_stocked_product_barcode_id = $sold_stocked_product_barcode->id;
                    $productReturn->request_type = $request->request_type;
                    $productReturn->reason = $request->reason;
                    $productReturn->comment = $request->comment;
                    $productReturn->status = 'requested';
                    $productReturn->updated_by = Auth::id();
                    $productReturn->save();
                    DB::commit();
                    return response()->json(['code' => 1, 'msg' => 'Product Return request edited']);
                }

                $product_return = ProductReturn::create([
                    'vendor_id'                           => $sold_stocked_product_barcode->vendor_id,
                    'product_id'                          => $sold_stocked_product_barcode->product_id,
                    'warehouse_id'                        => $sold_stocked_product_barcode->productStockDetailFromBarcode->warehouse_id,
                    'returned_stocked_product_barcode'    => trim($sold_stocked_product_barcode->bar_code),
                    'returned_stocked_product_barcode_id' => $sold_stocked_product_barcode->id,
                    'request_type'                        => $request->request_type,
                    'reason'                              => $request->reason,
                    'comment'                             => $request->description,
                    'status'                              => $request->has('instant_exchange') ? 'approved' : 'requested',
                    'created_by'                          => Auth::id(),
                    'updated_by'                          => Auth::id(),
                ]);
                // instant exchange
                if (trim($product_return->status) == 'approved') {

                    // update sale detail return quantity
                    $sale_detail                  = $sold_stocked_product_barcode->saleDetail()->first();
                    $sale_detail->return_quantity = !empty($sale_detail->return_quantity) ? $sale_detail->return_quantity + 1 : 1;
                    $sale_detail->save();

                    // create new sale detail with referenced sale detail
                    $returned_sale_detail = SaleDetail::create([
                        'sale_id'                  => $sale_detail->sale_id,
                        'vendor_id'                => $sale_detail->vendor_id,
                        'product_id'               => $sale_detail->product_id,
                        'warehouse_id'             => $sale_detail->warehouse_id,
                        'quantity'                 => 1,
                        'return_exchange_reference'         => $sale_detail->id, //referenced sale detail row (as there may be different sale_detail according to attribute)
                        'per_price'                => $sale_detail->per_price,
                        'total'                    => $sale_detail->per_price,
                    ]);
                    if(!empty($sale_detail->saleAttributeDetails)){
                        foreach($sale_detail->saleAttributeDetails as $k => $v){
                            $attribute_info = ProductAttribute::where('id', $v['product_attribute_id'])->select('name')->first();
                            $attribute_maps_info = ProductAttributeMap::where('id', $v['product_attribute_map_id'])->select('value')->first();
                            SaleAttributeDetails::create([
                                'sale_detail_id' => $sale_detail->id,
                                'product_attribute_id' => $v['product_attribute_id'],
                                'product_attribute_name' => $attribute_info['name'],
                                'product_attribute_map_id' => $v['product_attribute_map_id'],
                                'product_attribute_map_name' => $attribute_maps_info['value'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }
                    }

                    // create new sale order with referenced sale detail
                    SaleOrder::create([
                        'sale_detail_id' => $returned_sale_detail->id,
                        'warehouse_id' => $sale_detail->warehouse_id,
                        'status' => 'delivered',
                        'submitted_at' => date('Y-m-d H:i:s'),
                        'confirmed_at' => date('Y-m-d H:i:s'),
                        'processed_at' => date('Y-m-d H:i:s'),
                        'shipped_at' => date('Y-m-d H:i:s'),
                        'delivered_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id,
                    ]);

                    //update existing returned product info
                    $sold_stocked_product_barcode->product_return_id    = $product_return->id;
                    $sold_stocked_product_barcode->product_return_count = intval($sold_stocked_product_barcode->product_return_count) + 1;
                    $sold_stocked_product_barcode->updated_at           = date('Y-m-d H:i:s');
                    $sold_stocked_product_barcode->save();

                    // select a product for exchange and add sale_deatil_id in new exchanged product barcode
                    $exchanged_stocked_product_barcode                 = StockedProductBarcode::where('bar_code',trim($request->exchanged_stocked_product_barcode))->first();
                    $exchanged_stocked_product_barcode->sale_detail_id = $returned_sale_detail->id;
                    $exchanged_stocked_product_barcode->save();

                    //update return quantity and exchnaged quantity in pool
                    $productPool = $sold_stocked_product_barcode->saleDetail->productPoolDetails->productPool;
                    $productPool->return_quantity = !empty($productPool->return_quantity) ?  $productPool->return_quantity + 1 : 1;
                    $productPool->exchange_quantity = !empty($productPool->exchange_quantity) ?  $productPool->exchange_quantity + 1 : 1;
                    $productPool->updated_at = Carbon::now();
                    $productPool->updated_by = Auth::id();
                    $productPool->save();

                    //update return quantity and exchnaged quantity in stock pool
                    $productPoolStockDetail = $sold_stocked_product_barcode->productStockDetailFromBarcode->productPoolDetails->first();
                    $productPoolStockDetail->return_quantity = !empty($productPoolStockDetail->return_quantity) ?  $productPoolStockDetail->return_quantity + 1 : 1;
                    $productPoolStockDetail->exchange_quantity = !empty($productPoolStockDetail->exchange_quantity) ?  $productPoolStockDetail->exchange_quantity + 1 : 1;
                    $productPoolStockDetail->save();

                    //create new product pool sale detail
                    $productPoolSaleDeatil = new ProductPoolSaleDetail();
                    $productPoolSaleDeatil->sale_detail_id = $returned_sale_detail->id;
                    $productPoolSaleDeatil->product_pool_id = $productPool->id;
                    $productPoolSaleDeatil->save();

                    //create new product pool product return detail
                    $productPoolProductReturn = new ProductPoolProductReturn();
                    $productPoolProductReturn->product_return_id = $product_return->id;
                    $productPoolProductReturn->product_pool_id = $productPool->id;
                    $productPoolProductReturn->save();

                    // update stock status in original stock detail, product pool, product pool stock detail
                    if ($productPoolStockDetail->available_quantity <= 0) {
                        $stock_detail = $sold_stocked_product_barcode->productStockDetailFromBarcode->first();
                        $stock_detail->status = 'stock_out';
                        $stock_detail->save();

                        $productPoolStockDetail->stock_status = 'stock_out';
                        $productPoolStockDetail->save();
                    }
                    if($productPool->available_quantity <= 0) {
                        $productPool->stock_status = 'stock_out';
                        $productPool->save();
                    }
                    //update exchange product barcode in product_return
                    $product_return->exchanged_stocked_product_barcode_id = $exchanged_stocked_product_barcode->id;
                    $product_return->exchanged_stocked_product_barcode    = trim($exchanged_stocked_product_barcode->bar_code);
                    $product_return->approved_request_type    = trim($request->request_type);
                    $product_return->save();
                }
            } else {
                DB::rollBack();
                return response()->json(['code' => 0, 'msg' => 'Stock Unavailable']);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['code' => 0, 'msg' => 'Failed to process', 'raw' => $exception->getMessage()]);
        }
        DB::commit();
        return response()->json(['code' => 1, 'msg' => 'Product Return request submitted']);
    }
    public function destroyReturnRequest($return_id) {
        $productReturn = ProductReturn::find($return_id);
        if($productReturn->status != 'requested'){
            return response()->json(['code'=>0,'msg'=>'Sorry!! This request is in processing/processed state']);
        }
        $productReturn->delete();
        return response()->json(['code'=>1, 'msg'=>'Successfully Deleted']);
    }
    public function editReturnRequest($return_id)
    {
        $productReturn = ProductReturn::find($return_id);
//        if ($productReturn->warehouse_id == auth()->user()->warehouse_id) {
            return view('retail_sell.return_request', compact('productReturn'));
//        } else {
//            abort(404);
//        }

    }

//    public function saleProductStore(Request $request)
//    {
//        if (!\request()->ajax()) {
//            abort(404);
//        }
//        dd($request->all());
//        $pos_customer_id = null;
//        $mp_customer_id = null;
//        if(!empty(request()->customer_id)){
//            $customer_id = explode("_",$request->customer_id);
//            if ($customer_id[0] == 'pos'){
//                $pos_customer_id = $customer_id[1];
//            }elseif ($customer_id[0] == 'mp') {
//                $mp_customer_id = $customer_id[1];
//            }
//        }
//        $vendor_id = auth()->user()->vendor_id;
//        $stock_detail_id_arr = $request->stock_detail_id;
//        $stocked_product_barcode_id_arr = $request->stocked_product_barcode_id;
//        $product_id_arr = $request->product;
//        $warehouse_id_arr = $request->warehouse;
//        $attribute_id_arr = $request->attribute;
//        $attribute_map_id_arr = $request->attribute_map;
//        $quantity_arr = $request->quantity;
//        $per_price_arr = $request->per_price;
//        $total_arr = $request->total;
//        $sub_total = $request->sub_total;
//        $tax = $request->tax;
//        $shipping_charge = $request->shipping_charge;
//        $discount = $request->discount;
//        $final_total = $request->final_total;
//        $total_quantity = array_sum($quantity_arr);
//        $per_discount = $discount / $total_quantity;
//        $validator = Validator::make($request->all(), [
//            'customer_id' => 'required|string',
//            "sub_total" => "required|numeric",
//            "tax" => "required|numeric",
//            "shipping_charge" => "required|numeric",
//            "discount" => "required|numeric",
//            "final_total" => "required|numeric",
//
//            "product" => "required|array",
//            "product.*" => "required|integer",
//
//            "warehouse" => "required|array",
//            "warehouse.*" => "required|integer",
//
//            "attribute" => "required|array",
//            "attribute.*" => "required|string",
//
//            "attribute_map" => "required|array",
//            "attribute_map.*" => "required|string",
//
//            "quantity" => "required|array",
//            "quantity.*" => "required|integer",
//
//            "per_price" => "required|array",
//            "per_price.*" => "required|numeric",
//
//            "total" => "required|array",
//            "total.*" => "required|numeric",
//        ], [
//            'customer_id.required' => 'Customer field is required',
//            'customer_id.string' => 'Customer  field must be an String',
//        ]);
//
//
//        if ($validator->fails()) {
//            return response()->json(['errors' => $validator->errors()->all()]);
//        } else {
//
//            //start==> first check quantity is available or not
//            foreach ($product_id_arr as $key => $value) // start foreach loop
//            {
//                // check quantity
//                $product_qty = StockDetail::with(['productPoolDetails' => function($q){
//                    $q->with('productPool');
//                }])->where([
//                    'id' => $stock_detail_id_arr[$key],
//                    'vendor_id' => $vendor_id,
//                    'product_id' => $value,
//                    'warehouse_id' => $warehouse_id_arr[$key],
//                ])->first();
//
//                if ($product_qty->productPoolDetails->available_quantity < $quantity_arr[$key]) {
//                    return response()->json(['quantity_error' => [
//                        'stock_detail_id' => $product_qty->id,
//                    ]
//                    ]);
//                }
//            }
//            //end==> first check quantity is available or not
//            DB::beginTransaction();
//            try {
//                $vC = auth()->user()->vendor->active_sale_commission;
//                $swg = $final_total * ($vC/100);
//                $vwg = $final_total - $swg;
//                $in_no = Str::random(5);
//                $unique_invoice_no = Sale::where(['invoice_no' => $in_no, 'vendor_id' => $vendor_id,])->first();
//                if ($unique_invoice_no) {
//                    $in_no .= Str::random(2);
//                }
//                if ($pos_customer_id == null){
//                    $type_of_sale = 2;
//                }else{
//                    $type_of_sale = 1;
//                }
//                $sale = Sale::create([
//                    'vendor_id' => $vendor_id,
//                    'pos_customer_id' => $pos_customer_id,
//                    'marketplace_user_id' => $mp_customer_id,
//                    'invoice_no' => $in_no,
//                    'type_of_sale' => $type_of_sale,
//                    'items' => count($product_id_arr),
//                    'sub_total' => $sub_total,
//                    'tax' => $tax,
//                    'shipping_charge' => $shipping_charge,
//                    'discount' => $discount,
//                    'final_total' => $final_total,
//                    'commission_percentage' => $vC,
//                    'superadmin_will_get' => $swg,
//                    'vendor_will_get' => $vwg,
//                    'user_warehouse_id' => Auth::user()->warehouse_id ?? 0,
//                    'created_by' => auth()->id(),
//                    'updated_by' => auth()->id(),
//                ]);
//
//                foreach ($product_id_arr as $key => $value) // start foreach loop
//                {
//                    // check quantity
//                    $productStockDetail = StockDetail::with(['productPoolDetails' => function($q){
//                        $q->with('productPool');
//                    }])->where([
//                        'id' => $stock_detail_id_arr[$key],
//                        'vendor_id' => $vendor_id,
//                        'product_id' => $value,
//                        'warehouse_id' => $warehouse_id_arr[$key],
//                    ])->first();
//                    if ($productStockDetail->productPoolDetails->available_quantity >= $quantity_arr[$key]) {
//
//                        // Start Profit
//                        $product = Product::where('id',$value)->where('vendor_id',$vendor_id)->first();
//                        $s_d = SaleDetail::where('product_id',$value)->where('vendor_id',$vendor_id)->latest()->first();
//                        $total_cumulative_purchase_price = 0;
//                        $total_cumulative_sold_price = 0;
//                        if (empty($s_d)){
//                            $total_cumulative_purchase_price = $quantity_arr[$key] * $product->average_purchase_price;
//                            $total_cumulative_sold_price = $quantity_arr[$key] * ($per_price_arr[$key] - $per_discount);
//                        }else{
//                            $total_cumulative_purchase_price = $quantity_arr[$key] * $product->average_purchase_price;
//                            $total_cumulative_sold_price = $quantity_arr[$key] * ($per_price_arr[$key] - $per_discount);
//                        }
//
//                        $sale_detail = SaleDetail::create([
//                            'sale_id' => $sale->id,
//                            'vendor_id' => $vendor_id,
//                            'product_id' => $value,
//                            'average_purchase_price' => $product->average_purchase_price,
//                            'total_cumulative_purchase_price' => $total_cumulative_purchase_price,
//                            'total_cumulative_sold_price' => $total_cumulative_sold_price,
//                            'total_cumulative_profit' => ($total_cumulative_sold_price - $total_cumulative_purchase_price),
//                            'warehouse_id' => $warehouse_id_arr[$key],
//                            'quantity' => $quantity_arr[$key],
//                            'per_price' => $per_price_arr[$key],
//                            'total' => $total_arr[$key],
//                        ]);
//                        // End Profit
//
//                        $this_attribute_id_arr = explode(',', $attribute_id_arr[$key]);
//                        $this_attribute_map_id_arr = explode(',', $attribute_map_id_arr[$key]);
//                        foreach ($this_attribute_id_arr as $k => $v) {
//                            $attribute_info = ProductAttribute::where('id', $v)->select('name')->first();
//                            $attribute_maps_info = ProductAttributeMap::where('id', $this_attribute_map_id_arr[$k])->select('value')->first();
//                            SaleAttributeDetails::create([
//                                'sale_detail_id' => $sale_detail->id,
//                                'product_attribute_id' => $v,
//                                'product_attribute_name' => $attribute_info['name'],
//                                'product_attribute_map_id' => $this_attribute_map_id_arr[$k],
//                                'product_attribute_map_name' => $attribute_maps_info['value'],
//                                'created_at' => date('Y-m-d H:i:s'),
//                                'updated_at' => date('Y-m-d H:i:s'),
//                            ]);
//                        }
//
//                        SaleOrder::create([
//                            'sale_detail_id' => $sale_detail->id,
//                            'warehouse_id' => $warehouse_id_arr[$key],
//                            'status' => 'delivered',
//                            'submitted_at' => date('Y-m-d H:i:s'),
//                            'confirmed_at' => date('Y-m-d H:i:s'),
//                            'processed_at' => date('Y-m-d H:i:s'),
//                            'shipped_at' => date('Y-m-d H:i:s'),
//                            'delivered_at' => date('Y-m-d H:i:s'),
//                            'created_by' => Auth::user()->id,
//                            'updated_by' => Auth::user()->id,
//                        ]);
//
//
//                        $pool = $productStockDetail->productPoolDetails->productPool;
//                        $pool->pos_order_submitted_quantity = !empty($pool->pos_order_submitted_quantity) ?  $pool->pos_order_submitted_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $pool->pos_order_confirmed_quantity = !empty($pool->pos_order_confirmed_quantity) ?  $pool->pos_order_confirmed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $pool->pos_order_processed_quantity = !empty($pool->pos_order_processed_quantity) ?  $pool->pos_order_processed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $pool->pos_order_shipped_quantity = !empty($pool->pos_order_shipped_quantity) ?  $pool->pos_order_shipped_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $pool->pos_order_delivered_quantity = !empty($pool->pos_order_delivered_quantity) ?  $pool->pos_order_delivered_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $pool->updated_by = auth()->user()->id;
//                        $pool->updated_at = Carbon::now();
//                        $pool->save();
//
//
//                        $stockPoolDetail = $productStockDetail->productPoolDetails;
//                        $stockPoolDetail->pos_order_submitted_quantity = !empty($stockPoolDetail->pos_order_submitted_quantity) ?  $stockPoolDetail->pos_order_submitted_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $stockPoolDetail->pos_order_confirmed_quantity = !empty($stockPoolDetail->pos_order_confirmed_quantity) ?  $stockPoolDetail->pos_order_confirmed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $stockPoolDetail->pos_order_processed_quantity = !empty($stockPoolDetail->pos_order_processed_quantity) ?  $stockPoolDetail->pos_order_processed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $stockPoolDetail->pos_order_shipped_quantity = !empty($stockPoolDetail->pos_order_shipped_quantity) ?  $stockPoolDetail->pos_order_shipped_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $stockPoolDetail->pos_order_delivered_quantity = !empty($stockPoolDetail->pos_order_delivered_quantity) ?  $stockPoolDetail->pos_order_delivered_quantity + $quantity_arr[$key] : $quantity_arr[$key];
//                        $stockPoolDetail->save();
//
//                        $saleDetailPool = new ProductPoolSaleDetail();
//                        $saleDetailPool->sale_detail_id = $sale_detail->id;
//                        $saleDetailPool->product_pool_id = $pool->id;
//                        $saleDetailPool->save();
//
//                        if (isset($stocked_product_barcode_id_arr[$key]) && $stocked_product_barcode_id_arr[$key] != '') {
//                            $stocked_product_barcode_ids = explode(',', $stocked_product_barcode_id_arr[$key]);
//                            $stocked_product_barcodes = StockedProductBarcode::whereIn('id', $stocked_product_barcode_ids);
//                            $stocked_product_barcodes->update([
//                                'sale_detail_id' => $sale_detail->id
//                            ]);
//                        }
//
//                        // update stock status in original stock detail, product pool, product pool stock detail
//                        if ($stockPoolDetail->available_quantity <= 0) {
//                            $productStockDetail->status = 'stock_out';
//                            $productStockDetail->save();
//
//                            $stockPoolDetail->stock_status = 'stock_out';
//                            $stockPoolDetail->save();
//                        }
//                        if ($pool->available_quantity <= 0) {
//                            $pool->stock_status = 'stock_out';
//                            $pool->save();
//                        }
//                    }
//                } // end foreach loop
//            } catch (\Exception $exception) {
//                DB::rollBack();
//                return response()->json(['failed' => $exception->getMessage()]);
//            }
//            DB::commit();
//            return response()->json(['success' => $sale->id]);
//        }
//
//    }
    public function saleProductStore(Request $request)
    {
        if (!\request()->ajax()) {
            abort(404);
        }

        $vendor_id = auth()->user()->vendor_id;
        $stock_detail_id_arr = $request->stock_detail_id;
        //$stocked_product_barcode_id_arr = $request->stocked_product_barcode_id;
        $product_id_arr = $request->product;
        $warehouse_id_arr = $request->warehouse;
        $attribute_id_arr = $request->attribute;
        $attribute_map_id_arr = $request->attribute_map;
        $quantity_arr = $request->quantity;
        $per_price_arr = $request->per_price;
        $total_arr = $request->total;
        $sub_total = $request->sub_total;
        $tax = $request->tax;
        $shipping_charge = $request->shipping_charge;
        $discount = $request->discount;
        $discount_percentage = $request->discount_percentage;
        $final_total = $request->final_total;
        //$total_quantity = array_sum($quantity_arr);
        //$per_discount = $discount / $total_quantity;
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
            "sub_total" => "required|numeric",
            "tax" => "required|numeric",
            "shipping_charge" => "required|numeric",
            "discount" => "required|numeric",
            "discount_percentage" => "required|numeric",
            "final_total" => "required|numeric",

            "product" => "required|array",
            "product.*" => "required|integer",

            "warehouse" => "required|array",
            "warehouse.*" => "required|integer",

            "attribute" => "required|array",
            "attribute.*" => "required|string",

            "attribute_map" => "required|array",
            "attribute_map.*" => "required|string",

            "quantity" => "required|array",
            "quantity.*" => "required|integer",

            "per_price" => "required|array",
            "per_price.*" => "required|numeric",

            "total" => "required|array",
            "total.*" => "required|numeric",
        ], [
            'customer_id.required' => 'Customer field is required',
            'customer_id.string' => 'Customer  field must be an String',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }

        //Calculations
        foreach ($product_id_arr as $key => $value){
                // check quantity
                $product_qty = StockDetail::with(['productPoolDetails' => function($q){
                    $q->with('productPool');
                }])->where([
                    'id' => $stock_detail_id_arr[$key],
                    'vendor_id' => $vendor_id,
                    'product_id' => $value,
                    'warehouse_id' => $warehouse_id_arr[$key],
                ])->first();

                if ($product_qty->productPoolDetails->available_quantity < $quantity_arr[$key]) {
                    return response()->json(['quantity_error' => [
                        'stock_detail_id' => $product_qty->id,
                    ]
                    ]);
                }
        }
        if (!empty($product_id_arr)) {
            $inv_prefix = DB::table('admin_configs')
                ->select('value')
                ->where('name', 'invoice_prefix')
                ->first();

            //Invoice Prefix
            if (empty($inv_prefix)) {
                $invoice_prefix = 'INV';
            } else {
                $invoice_prefix = $inv_prefix->value;
            }
        }
        try {
            DB::beginTransaction();
            if (!empty($product_id_arr)) {
                //Invoice Prefix
                do {
                    $in_no = strtoupper(uniqid($invoice_prefix) . rand(10, 99));
                    $unique_invoice_no = Sale::where(['invoice_no' => $in_no])->first();
                } while ($unique_invoice_no);
                $vC = auth()->user()->vendor->active_sale_commission;
                $swg = $final_total * ($vC/100);
                $vwg = $final_total - $swg;
                $sale = Sale::create([
                    'vendor_id' => $vendor_id,
                    //'vendor_id' => auth()->user()->vendor_id,
                    'type_of_sale' => 2,
                    'marketplace_user_id' => $request->customer_id,
                    'invoice_no' => $in_no,
                    'items' => count($product_id_arr),
                    'sub_total' => $sub_total,
                    'tax' => $tax,
                    //'marketplace_user_address_id' => $marketplace_user_address_id,
                    //'payment_method_id' => $payment_method_id,
                    //'shipping_method_id' => $delivery_type,
                    'shipping_charge' => $shipping_charge,
                    'discount' => $discount,
                    'discount_percentage' => $discount_percentage,
                    'final_total' => $final_total,
                    'commission_percentage' => $vC,
                    'superadmin_will_get' => $swg,
                    'vendor_will_get' => $vwg,
                    'status' => 'pending',
                    'delivery_status' => 'pending',
                    'payment_status' => 'pending',
                    'user_warehouse_id' => 0,
                    'created_by' => auth()->user()->id,
                    'updated_by' => 0,
                ]);

                foreach ($product_id_arr as $key => $value) // start foreach loop
                {
                    // check quantity
                    $productStockDetail = StockDetail::with(['productPoolDetails' => function($q){
                        $q->with('productPool');
                    }])->where([
                        'id' => $stock_detail_id_arr[$key],
                        'vendor_id' => $vendor_id,
                        'product_id' => $value,
                        'warehouse_id' => $warehouse_id_arr[$key],
                    ])->first();
                    $product_pool_id = $productStockDetail->productPoolDetails->productPool->id;
                    /// ===>> Start Profit && Loss
                    /*$product_data = Product::where('id',$value)->where('vendor_id',$vendor_id)->first();
                    $total_cumulative_purchase_price = ($value['quantity'] * $product_data->average_purchase_price);
                    $total_cumulative_sold_price = ($value['quantity'] * $value['price']);*/

                    $SaleDetail = SaleDetail::create([
                        'sale_id' => $sale->id,
                        'vendor_id' => $vendor_id,
                        'product_id' => $value,
                        /*'average_purchase_price' => $product_data->average_purchase_price,
                        'total_cumulative_purchase_price' => $total_cumulative_purchase_price,
                        'total_cumulative_sold_price' => $total_cumulative_sold_price,
                        'total_cumulative_profit' => ($total_cumulative_sold_price - $total_cumulative_purchase_price),*/
                        //'attribute_id' => isset($value['attribute_id']) ? $value['attribute_id'] : 0,
                        //'product_attribute_map_id' => isset($value['attribute_map_id']) ? $value['attribute_map_id'] : 0,
                        'quantity' => $quantity_arr[$key],
                        'status' => 'pending',
                        'per_price' => $per_price_arr[$key],
                        'total' => $total_arr[$key],
                        'warehouse_id' => 0,
                        'created_by' => auth()->user()->id,
                        //'updated_by' => 0,
                    ]);

                    $this_attribute_id_arr = explode(',', $attribute_id_arr[$key]);
                    $this_attribute_map_id_arr = explode(',', $attribute_map_id_arr[$key]);
                    foreach ($this_attribute_id_arr as $k => $v) {
                        $attribute_info = ProductAttribute::where('id', $v)->select('name')->first();
                        $attribute_maps_info = ProductAttributeMap::where('id', $this_attribute_map_id_arr[$k])->select('value')->first();
                        SaleAttributeDetails::create([
                            'sale_detail_id' => $SaleDetail->id,
                            'product_attribute_id' => $v,
                            'product_attribute_name' => $attribute_info['name'],
                            'product_attribute_map_id' => $this_attribute_map_id_arr[$k],
                            'product_attribute_map_name' => $attribute_maps_info['value'],
                            'created_at' => date('Y-m-d H:i:s'),
                            'updated_at' => date('Y-m-d H:i:s'),
                        ]);
                    }

                    SaleOrder::create([
                        'sale_detail_id'=>$SaleDetail->id,
                        'status'=>'submitted',
                        'submitted_at'=>date("Y-m-d H:i:s")
                    ]);

                    $p_p_data = DB::table('product_pools')
                        ->where('id',$product_pool_id)->first();
                    if ($p_p_data->mp_order_submitted_quantity == null ){
                        DB::table('product_pools')
                            ->where('id',$product_pool_id)
                            ->update(['mp_order_submitted_quantity' => 0]);
                    }
                    if ($p_p_data->mp_order_confirmation_pending == null ){
                        DB::table('product_pools')
                            ->where('id',$product_pool_id)
                            ->update(['mp_order_confirmation_pending' => 0]);
                    }
                    DB::table('product_pools')
                        ->where('id',$product_pool_id)
                        ->increment('mp_order_submitted_quantity',$quantity_arr[$key]);
                    DB::table('product_pools')
                        ->where('id',$product_pool_id)
                        ->increment('mp_order_confirmation_pending',$quantity_arr[$key]);
                    //
                    ProductPoolSaleDetail::create([
                        'product_pool_id'=>$product_pool_id,
                        'sale_detail_id'=>$SaleDetail->id
                    ]);
                } // end foreach loop
            }else{
                return response()->json(responseFormat(ERROR,'Product Out Of Stock'));
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(['failed' => $exception->getMessage()]);
        }
        DB::commit();
        return response()->json(['success' => $sale->id]);
    }

    public function SalePaymentWithAjax(Request $request)
    {

        if (!\request()->ajax()) {
            abort(404);
        }
        $last_sale_id = $request->last_sale_id;
        $pos_customer_id = null;
        $mp_customer_id = null;
        if(!empty(request()->customer_id)){
            $customer_id = explode("_",$request->customer_id);
            if ($customer_id[0] == 'pos'){
                $pos_customer_id = $customer_id[1];
            }elseif ($customer_id[0] == 'mp') {
                $mp_customer_id = $customer_id[1];
            }
        }
        $card_name = $request->card_name;
        $card_number = $request->card_number;
        $check_no = $request->check_no;
        $payment_amount = $request->payment_amount;
        $payment_type = $request->payment_type;
        $due = $request->due;
        $give_back = $request->back;
        $status = $request->status;
        $final_total = $request->final_total;

        // echo $check_no;

        $sale_check = Sale::where([
            'id' => $last_sale_id,
            'pos_customer_id' => $pos_customer_id,
            'marketplace_user_id' => $mp_customer_id,
            'vendor_id' => auth()->user()->vendor_id])->first();
        if ($sale_check) {
            $salePayment = new SalePayment();
            $salePayment->sale_id = $last_sale_id;
            $salePayment->vendor_id = auth()->user()->vendor_id;
            $salePayment->warehouse_id = auth()->user()->warehouse_id ?? 0;
            $salePayment->pos_customer_id = $pos_customer_id;
            $salePayment->marketplace_user_id = $mp_customer_id;
            $salePayment->final_total = $final_total;
            $salePayment->payment_by = $payment_type;
            $salePayment->check_no = $check_no;
            $salePayment->card_name = $card_name;
            $salePayment->card_number = $card_number;
            $salePayment->pay_amount = $payment_amount;
            $salePayment->due_amount = $due;
            $salePayment->give_back = $give_back;
            $salePayment->status = $status;
            $salePayment->save();

            $sale_check = Sale::findOrFail($last_sale_id);
            $sale_check->status = $request->status;
            $sale_check->save();
            return response()->json('payment_success');
        }

    }

    public function mpCustomerAjax (Request $request)
    {
        if ($request->search){
            $mp_customers = MarketplaceUser::where('name','like',"%{$request->search}%")->get();
        }else{
            $mp_customers = MarketplaceUser::paginate(5);
        }

        $customers = array();
        foreach ($mp_customers as $value){
            $customers[$value->id] = $value->name .' ['. $value->email .']';
        }
        return $customers;
    }
}
