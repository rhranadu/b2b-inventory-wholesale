<?php

namespace App\Http\Controllers;


use App\Sale;
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
use DataTables;

class PosController extends Controller {

    public function index() {
        if (strtolower(auth()->user()->user_role->name) != 'pos') {
            if (!$this->current_warehouse_id) {
                return redirect('/')->with('error', 'Warehouse not found');
            }
        }
        return view('pos.index');
    }

    public function products(Request $request)
    {
        $without_barcode = explode(',', $request->without_barcode);
        $search_key = $request->search_key;
        $category_id = $request->product_category_id;
        $brand_id = $request->product_brand_id;
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
            ->where('stock_details.warehouse_id', $this->current_warehouse_id)
            ->where(function ($query) use ($search_key) {
                $query->where('products.name', 'like', '%' . $search_key . '%')
                    ->orWhereHas('productBarcodesFromStockDetail', function ($query2) use ($search_key) {
                        $query2->where('bar_code', 'like', '%' . $search_key . '%');
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
        return view('pos.products', compact('products', 'without_barcode', 'search_key'));
    }

    public function receipt($sale_id) {
        $saleData = Sale::find($sale_id);
        return view('pos.receipt', compact('saleData'));
    }

    public function chalan($sale_id) {
        $saleData = Sale::find($sale_id);
        return view('pos.chalan',compact('saleData'));
    }

    public function todaySales() {
        $sales = Sale::whereDate('created_at', date('Y-m-d'))->where('user_warehouse_id', $this->current_warehouse_id)->get();
        return view('pos.today_sales', compact('sales'));
    }

    public function payOption($sale_id) {
        $saleData = Sale::find($sale_id);
        return view('pos.payment', compact('saleData'));
    }

    public function dueOrders() {
        return view('pos.due_orders');
    }

    public function dueOrdersListByAjax(Request $request){
        if (!$request->ajax())
        {
            abort(404);
        }
        $sales = Sale::where('user_warehouse_id', $this->current_warehouse_id)
            ->get()
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
        $product_return = ProductReturn::with(['product'])->where(['vendor_id' => Auth::user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id, 'status' => 'requested'])->get();
        return view('pos.pending_return_request', compact('product_return'));
    }
    public function returnRequest(Request $request) {
        return view('pos.return_request');
    }

    public function exchangeProductDetail(Request $request) {
        $productDetail = [];
        $barcode_product = StockedProductBarcode::with(['productStockDetailFromBarcode' => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
                $query->where('warehouse_id', Auth::user()->warehouse_id);
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
        return view('pos.exchange_product_detail', compact('barcode_product', 'stock_detail', 'product','product_attribute'));

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
                    $query->where('warehouse_id', Auth::user()->warehouse_id);
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
            return view('pos.request_detail', compact('return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute','from_return_request'));
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
                        $query->where('warehouse_id', Auth::user()->warehouse_id);
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
            return view('pos.request_detail', compact('return_detail','return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute','from_return_request'));
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
                    $query->where('warehouse_id', Auth::user()->warehouse_id);
                }]);
                $query->where('vendor_id', Auth::user()->vendor_id);
                $query->where('warehouse_id', Auth::user()->warehouse_id);
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
        if ($productReturn->warehouse_id == auth()->user()->warehouse_id) {
            return view('pos.return_request', compact('productReturn'));
        } else {
            abort(404);
        }

    }
}
