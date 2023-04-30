<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\ProductReturn;
use App\Sale;
use App\SaleDetail;
use App\SaleOrder;
use App\SalePayment;
use App\SaleAttributeDetails;
use App\ProductAttributeMap;
use App\ProductAttribute;
use App\StockedProductBarcode;
use App\ProductPoolSaleDetail;
use App\ProductPoolProductReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ProductReturnController extends Controller {

    public function index(Request $request) {
        if (!$request->ajax()) {
            return view('product_returns.index');
        }
        $product_return = ProductReturn::with(['product'])->where(['vendor_id' => Auth::user()->vendor_id /*, 'warehouse_id' => Auth::user()->warehouse_id*/])->get();
        foreach ($product_return as $rtn) {
            $rtn->reason  = Str::limit($rtn->reason, 50);
            $rtn->comment = Str::limit($rtn->comment, 50);
        }
        return DataTables::of($product_return)
            ->addIndexColumn()
            ->editColumn('created_at', function ($product_return) {
                return date('d F Y,  h:i:s A',strtotime($product_return->created_at));
            })
            ->editColumn('request_type', function ($product_return) {
                if ($product_return->request_type == 'refund') {
                    return '<span class="badge badge-warning">Refund</span>';
                } elseif ($product_return->request_type == 'exchange') {
                    return '<span class="badge badge-danger">Exchange</span>';
                } else {
                    return '<span class="badge badge-success">Return Only</span>';
                }
            })
            ->editColumn('approved_request_type', function ($product_return) {
                if ($product_return->approved_request_type == 'refund') {
                    return '<span class="badge badge-warning">Refund</span>';
                } elseif ($product_return->approved_request_type == 'exchange') {
                    return '<span class="badge badge-danger">Exchange</span>';
                } elseif($product_return->approved_request_type == 'return') {
                    return '<span class="badge badge-success">Return Only</span>';
                } else {
                    return '<span class="badge badge-info">Not Approved Yet</span>';
                }
            })
            ->editColumn('status', function ($product_return) {
                if ($product_return->status == 'requested') {
                    return '<span class="badge badge-warning">Requested</span>';
                } elseif ($product_return->status == 'approved') {
                    return '<span class="badge badge-success">Approved</span>';
                } else {
                    return '<span class="badge badge-danger">Rejected</span>';
                }
            })
            ->addColumn('action', function ($product_return) {
                $md = '<div class="btn-group">';
                $md .= '<a href="#" class="btn btn-sm btn-icon btn-success return-detail" '
                .'data-toggle="tooltip" data-placement="auto" title="View" '
                .'data-original-title="View" data-key="' . $product_return->id . '" '
                .'data-rtn-barcode ="' . $product_return->returned_stocked_product_barcode . '">'
                .'<i class="fa fa-eye"></i></a>';
                if ($product_return->status == 'requested') {
                    $md .= '<a href="#" class="btn btn-sm btn-icon btn-info take-action" '
                    .'data-toggle="tooltip" data-placement="auto" title="Take Action" '
                    .'data-original-title="View" data-key="' . $product_return->id . '" '
                    .'data-rtn-barcode ="' . $product_return->returned_stocked_product_barcode . '">'
                    .'<i class="fa fa-check"></i></a>';
                    $md .= '<a href="#" '
                    .'class="btn btn-sm btn-icon btn-danger delete-return-request" '
                    .'data-key="'.$product_return->id.'" '
                    .'data-toggle="tooltip" '
                    .'data-placement="auto" title="DELETE" data-original-title="DELETE"> '
                    .'<i class="fa fa-trash"></i></a>';

                    $md .= '<a target="_blank" href="/admin/product_returns/' . $product_return->id . '/edit" '
                    .'class="btn btn-sm btn-icon btn-warning edit-return-request" '
                    .'data-key="'.$product_return->id.'" data-toggle="tooltip" data-placement="auto" title="EDIT" '
                    .'data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>';
                }
                $md .= '</div>';
                return $md;
            })
            ->rawColumns(['request_type','approved_request_type','status', 'action','created_at'])
            ->make(true);
    }

    public function returnRequestEndpointForm(Request $request) {
        return view('product_returns.return_form');
    }

    public function getReturnedProductInfoWithAjax(Request $request) {
        if (!request()->ajax()) {
            abort(404);
        }

        $invoice_product = Sale::with(['saleDetails' => function ($query) {
                $query->with('product');
                $query->with('soldBarcode');
            }])
            ->where('invoice_no', trim($request->text))
            ->where('vendor_id', auth()->user()->vendor_id)
            ->first();

        return view('product_returns.get_returned_products',compact('invoice_product'));
    }
    public function getExchangedProductInfoWithAjax(Request $request) {
        if (!request()->ajax()) {
            abort(404);
        }

        $existing_product_return = ProductReturn::where('returned_stocked_product_barcode', trim($request->text))->orWhere('exchanged_stocked_product_barcode',trim($request->text))->first();

        if($existing_product_return){
            if($existing_product_return->exchanged_stocked_product_barcode == trim($request->text)){
                return response()->json(['code' => 0, 'error' => 'This product already given as exchnaged product']);
            }
            if ($existing_product_return->status == 'requested') {
                return response()->json(['code' => 0, 'error' => 'This product already have pending ' . $existing_product_return->request_type . ' request']);
            } elseif($existing_product_return->status == 'approved') {
                return response()->json(['code' => 0, 'error' => 'This product already have approved ' . $existing_product_return->approved_request_type . ' request']);
            } elseif($existing_product_return->status == 'rejected') {
                return response()->json(['code' => 0, 'error' => 'This product already have rejected ' . $existing_product_return->request_type . ' request']);
            }
        }
        $stocked_product = StockedProductBarcode::has('productStockDetailFromBarcode')
            ->has('productFromBarcode')
            ->with(['productStockDetailFromBarcode' => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
                $query->where('warehouse_id', Auth::user()->warehouse_id);
            }])
            ->with(['productFromBarcode' => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
            }])
            ->where('bar_code', $request->text)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->where(function ($query) {
                $query->where('sale_detail_id', 0);
                $query->orWhereNull('sale_detail_id');
            })
            ->where(function ($query) {
                $query->where('product_return_id', 0);
                $query->orWhereNull('product_return_id');
            })
            ->first();
        if ($stocked_product) {
            return response()->json([
                'code'          => 1,
                'barcode'       => $stocked_product->toArray(),
                'available_qty' => $stocked_product->productStockDetailFromBarcode->quantity,
            ]);
        } else {
            return response()->json(['code' => 0, 'error' => 'Invalid Product']);
        }
    }

    public function returnRequestDetail(Request $request) {
        if(!$request->has('return_product_id') || empty($request->return_product_id)){
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
            return view('product_returns.request_detail', compact('return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute'));
        } else {
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
                ->with(['exchangedProductBarcode' => function ($query) {
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
            $exchanged_barcode = $product_return_detail->exchangedProductBarcode;

            $return_sale_detail = $product_return_detail->returnedProductBarcode->saleDetail;
            $return_sale = $product_return_detail->returnedProductBarcode->saleDetail->sale;
            $return_sale_payment = $product_return_detail->returnedProductBarcode->saleDetail->sale->payment->all();
            $return_stock_detail = $product_return_detail->returnedProductBarcode->productStockDetailFromBarcode;
            $return_product = $product_return_detail->returnedProductBarcode->productFromBarcode;
            $return_product_attribute = $product_return_detail->returnedProductBarcode->purchaseDetail->attributeDetail->all();

            if(!empty($exchanged_barcode)){
                $exchanged_sale_detail = $product_return_detail->exchangedProductBarcode->saleDetail;
                $exchanged_sale = $product_return_detail->exchangedProductBarcode->saleDetail->sale;
                $exchanged_sale_payment = $product_return_detail->exchangedProductBarcode->saleDetail->sale->payment->all();
                $exchanged_stock_detail = $product_return_detail->exchangedProductBarcode->productStockDetailFromBarcode;
                $exchanged_product = $product_return_detail->exchangedProductBarcode->productFromBarcode;
                $exchanged_product_attribute = $product_return_detail->exchangedProductBarcode->purchaseDetail->attributeDetail->all();
                return view('product_returns.request_detail', compact('return_detail','return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute','exchanged_barcode','exchanged_sale_detail','exchanged_sale','exchanged_sale_payment', 'exchanged_stock_detail', 'exchanged_product','exchanged_product_attribute'));
            }
            return view('product_returns.request_detail', compact('return_detail','return_barcode','return_sale_detail','return_sale','return_sale_payment', 'return_stock_detail', 'return_product','return_product_attribute'));
        }
    }
    public function notSoldProductDetail(Request $request) {
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
        return view('product_returns.not_sold_product_detail', compact('barcode_product', 'stock_detail', 'product','product_attribute'));

    }
    public function returnRequestAction(Request $request) {
        $product_return_id = $request->product_return_id;
        $product_return = ProductReturn::with(['returnedProductBarcode' => function ($query) {
            $query->with(['productPoolStockDetail' => function ($query) {
                $query->with('productPool');
            }]);
            $query->with(['saleDetail' => function ($query) {
                $query->with(['sale' => function($query) {
                    $query->with('payment');
                }]);
                $query->where('vendor_id', Auth::user()->vendor_id);
            }]);
            $query->with(['productStockDetailFromBarcode' => function ($query) {
                $query->with(['productPoolStockDetail' => function($query){
                    $query->where('warehouse_id', Auth::user()->warehouse_id);
                }]);
                $query->where('vendor_id', Auth::user()->vendor_id);
                $query->where('warehouse_id', Auth::user()->warehouse_id);
            }]);
            $query->with(['productFromBarcode' => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
            }]);
        }])
        ->where('id', $product_return_id)->first();
        DB::beginTransaction();
        try {
            // update sale detail return quantity
            $sale_detail                  = $product_return->returnedProductBarcode->saleDetail;
            $sale_detail->return_quantity = !empty($sale_detail->return_quantity) ? $sale_detail->return_quantity + 1 : 1;
            $sale_detail->save();

            $productPool = $product_return->productPoolDetails->productPool;
            $productPool->return_quantity = !empty($productPool->return_quantity) ?  $productPool->return_quantity + 1 : 1;
            $productPool->save();

            //update return quantity and exchnaged quantity in stock pool
            $productPoolStockDetail = $product_return->productStockDetailFromBarcode->productPoolDetails->first();
            $productPoolStockDetail->return_quantity = !empty($productPoolStockDetail->return_quantity) ?  $productPoolStockDetail->return_quantity + 1 : 1;
            $productPoolStockDetail->save();


            //update existing returned product barcode info
            $sold_stocked_product_barcode = $product_return->returnedProductBarcode;
            $sold_stocked_product_barcode->product_return_id    = $product_return->id;
            $sold_stocked_product_barcode->product_return_count = intval($sold_stocked_product_barcode->product_return_count) + 1;
            $sold_stocked_product_barcode->updated_at           = date('Y-m-d H:i:s');
            $sold_stocked_product_barcode->save();

            if(trim($request->request_type) == 'exchange'){

                // if only exchanged
                // create new sale detail with referenced sale detail
                $returned_sale_detail = SaleDetail::create([
                    'sale_id'                  => $sale_detail->sale_id,
                    'vendor_id'                => $sale_detail->vendor_id,
                    'product_id'               => $sale_detail->product_id,
                    'warehouse_id'             => $sale_detail->warehouse_id,
                    'quantity'                 => 1,
                    'return_exchange_reference'=> $sale_detail->id, //referenced sale detail row (as there may be different sale_detail according to attribute)
                    'per_price'                => $sale_detail->per_price,
                    'total'                    => $sale_detail->per_price,
                ]);

                //create new product pool sale detail
                $productPoolSaleDeatil = new ProductPoolSaleDetail();
                $productPoolSaleDeatil->sale_detail_id = $returned_sale_detail->id;
                $productPoolSaleDeatil->product_pool_id = $productPool->id;
                $productPoolSaleDeatil->save();


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
                    'warehouse_id'   => $sale_detail->warehouse_id,
                    'status'         => 'submitted',
                    'submitted_at'   => date('Y-m-d H:i:s'),
                    'created_by'     => Auth::user()->id,
                    'updated_by'     => Auth::user()->id,
                ]);

                // update product poolexchange quantity
                $productPool->exchange_quantity = !empty($productPool->exchange_quantity) ?  $productPool->exchange_quantity + 1 : 1;
                $productPool->save();

                $productPoolStockDetail->exchange_quantity = !empty($productPoolStockDetail->exchange_quantity) ?  $productPoolStockDetail->exchange_quantity + 1 : 1;
                $productPoolStockDetail->save();


                // update stock status
                if ($productPoolStockDetail->available_quantity <= 0) {
                    $stock_detail = $product_return->returnedProductBarcode->productStockDetailFromBarcode;
                    $stock_detail->status = 'stock_out';
                    $stock_detail->save();

                    $productPoolStockDetail->stock_status = 'stock_out';
                    $productPoolStockDetail->save();
                }
                if($productPool->available_quantity <= 0) {
                    $productPool->stock_status = 'stock_out';
                    $productPool->save();
                }


                // select a product for exchange and add sale_deatil_id in new exchanged product barcode
                $stocked_product_barcode                 = StockedProductBarcode::where('bar_code', $request->exchanged_barcode)->first();
                $stocked_product_barcode->sale_detail_id = $returned_sale_detail->id;
                $stocked_product_barcode->save();

                //update exchanged product barcode info in product_return table
                $product_return->exchanged_stocked_product_barcode_id = $stocked_product_barcode->id;
                $product_return->exchanged_stocked_product_barcode    = trim($stocked_product_barcode->bar_code);
                $product_return->save();
            } elseif (trim($request->request_type) == 'refund'){
                $sale_payment = $product_return->returnedProductBarcode->saleDetail->sale->payment->first();
                SalePayment::create([
                    'sale_id'             => $sale_payment->sale_id,
                    'vendor_id'           => $sale_payment->vendor_id,
                    'warehouse_id'        => $sale_payment->warehouse_id,
                    'pos_customer_id'     => $sale_payment->pos_customer_id,
                    'marketplace_user_id' => $sale_payment->marketplace_user_id,
                    'final_total'         => floatval($sale_payment->final_total) - abs(floatval($request->refund_sale_pay_amount)),
                    'payment_by'          => $request->refund_sale_payment_by,
                    'check_no'            => $request->refund_sale_payment_by == 'check' ? ($request->refund_sale_check_no ?: null): null,
                    'card_name'           => $request->refund_sale_payment_by == 'card' ? ($request->refund_sale_card_name ?: null) : null,
                    'card_number'         => $request->refund_sale_payment_by == 'card' ? ($request->refund_sale_card_number ?: null) : null,
                    'pay_amount'          => -abs(floatval($request->refund_sale_pay_amount)),
                    'due_amount'          => 0,
                    'give_back'           => 0,
                    'status'              => 'FP',
                    'comment'              => 'refunded',
                    'created_at'          => date('Y-m-d H:i:s'),
                    'updated_at'          => date('Y-m-d H:i:s'),
                ]);
            }
            $product_return->approved_request_type    = trim($request->request_type);
            $product_return->status = 'approved';
            $product_return->save();

            $productPool->updated_at = Carbon::now();
            $productPool->updated_by = Auth::id();
            $productPool->save();

        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('admin.return.product')->with('error', $exception->getMessage());
        }
        DB::commit();
        return redirect()->route('admin.return.product')->with('success', 'Request Approved');
    }
    public function returnRequestReject(Request $request) {
        $product_return = ProductReturn::find($request->product_return_id);
        if($product_return->status != 'requested'){
            return back()->with('error', 'Sorry!! This request is already processed!!');
        }
        $product_return->status = 'rejected';
        $product_return->save();
        return redirect()->route('admin.return.product')->with('success', 'Request submitted');
    }
    public function edit(ProductReturn $productReturn)
    {
        if ($productReturn->vendor_id == auth()->user()->vendor_id) {
            return view('product_returns.return_form', compact('productReturn'));
        } else {
            abort(404);
        }

    }
    public function destroy(ProductReturn $productReturn) {
        if($productReturn->status != 'requested'){
            return response()->json(['msg'=>'Sorry!! This request is in processing/processed state'], Response::HTTP_FORBIDDEN);
        }
        $productReturn->delete();
        return response()->json(['msg'=>'Successfully Deleted'], Response::HTTP_OK);
    }
    public function returnRequestEndpointSubmit(Request $request) {
        //dd(Auth::user()->user_role->name);
        if (empty($request->reason)){
            return back()->with('error', 'Reason is Required for Product Return');
        }
        $bar_codes = [];
        $exchanged_bar_codes = [];
//        if ($request->request_type == 'refund'){
            $bar_codes = $request->return_product_bar_code;
//        }
//        else
        if ($request->request_type == 'exchange'){
            $exchanged_bar_codes = $request->exchanged_product_bar_code;
        }

        DB::beginTransaction();
        try {
            if (!empty($bar_codes)) {
                foreach ($bar_codes as $key => $barCode) {

                    $sold_stocked_product_barcode = StockedProductBarcode::has('productStockDetailFromBarcode')
                        ->has('productFromBarcode')
                        ->has('saleDetail')
                        ->with(['saleDetail' => function ($query) {
                            $query->with(['productPoolDetails' => function ($query) {
                                $query->with('productPool');
                            }]);
                            $query->where('vendor_id', Auth::user()->vendor_id);
                        }])
                        ->with(['productStockDetailFromBarcode' => function ($query) {
                            $query->with(['productPoolDetails' => function ($query) {
                                //$query->where('warehouse_id', Auth::user()->warehouse_id);
                            }]);
                            $query->where('vendor_id', Auth::user()->vendor_id);
                                //$query->where('warehouse_id', Auth::user()->warehouse_id);
                        }])
                        ->with(['productFromBarcode' => function ($query) {
                            $query->where('vendor_id', Auth::user()->vendor_id);
                        }])
                        ->where('bar_code', $barCode)
                        ->where('sale_detail_id', '!=', 0)
                        ->where('vendor_id', auth()->user()->vendor_id)
                        ->first();

                    if ($sold_stocked_product_barcode) {
                        if (!empty($request->id)) {
                            $productReturn = ProductReturn::find($request->id);
                            $productReturn->product_id = $sold_stocked_product_barcode->product_id;
                            $productReturn->returned_stocked_product_barcode =
                                trim($sold_stocked_product_barcode->bar_code);
                            $productReturn->returned_stocked_product_barcode_id = $sold_stocked_product_barcode->id;
                            $productReturn->request_type = $request->request_type;
                            $productReturn->reason = $request->reason;
                            $productReturn->comment = $request->comment;
                            $productReturn->status = 'requested';
                            $productReturn->updated_by = Auth::id();
                            $productReturn->save();
                            DB::commit();
                            return redirect()->route('admin.return.product')->with('success', 'Product Return request edited');
                        }

                        $product_return = ProductReturn::create([
                            'vendor_id' => $sold_stocked_product_barcode->vendor_id,
                            'product_id' => $sold_stocked_product_barcode->product_id,
                            'warehouse_id' => $sold_stocked_product_barcode->productStockDetailFromBarcode->warehouse_id,
                            'returned_stocked_product_barcode' => trim($sold_stocked_product_barcode->bar_code),
                            'returned_stocked_product_barcode_id' => $sold_stocked_product_barcode->id,
                            'request_type' => $request->request_type,
                            'reason' => $request->reason,
                            'comment' => $request->comment,
                            'status' => $request->has('instant_exchange') ? 'approved' : 'requested',
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                        ]);
                        // instant exchange
                        if ($request->request_type == 'exchange') {
                            if (trim($product_return->status) == 'approved') {

                                // update sale detail return quantity
                                $sale_detail = $sold_stocked_product_barcode->saleDetail()->first();
                                $sale_detail->return_quantity =
                                    !empty($sale_detail->return_quantity) ? $sale_detail->return_quantity + 1 : 1;
                                $sale_detail->save();

                                // create new sale detail with referenced sale detail
                                $returned_sale_detail = SaleDetail::create([
                                    'sale_id' => $sale_detail->sale_id,
                                    'vendor_id' => $sale_detail->vendor_id,
                                    'product_id' => $sale_detail->product_id,
                                    'warehouse_id' => $sale_detail->warehouse_id,
                                    'quantity' => 1,
                                    'return_exchange_reference' => $sale_detail->id, //referenced sale detail row (as there may be different sale_detail according to attribute)
                                    'per_price' => $sale_detail->per_price,
                                    'total' => $sale_detail->per_price,
                                ]);
                                if (!empty($sale_detail->saleAttributeDetails)) {
                                    foreach ($sale_detail->saleAttributeDetails as $k => $v) {
                                        $attribute_info =
                                            ProductAttribute::where('id', $v['product_attribute_id'])->select('name')->first();
                                        $attribute_maps_info =
                                            ProductAttributeMap::where('id', $v['product_attribute_map_id'])->select('value')->first();
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
                                $sold_stocked_product_barcode->product_return_id = $product_return->id;
                                $sold_stocked_product_barcode->product_return_count =
                                    intval($sold_stocked_product_barcode->product_return_count) + 1;
                                $sold_stocked_product_barcode->updated_at = date('Y-m-d H:i:s');
                                $sold_stocked_product_barcode->save();

                                // select a product for exchange and add sale_deatil_id in new exchanged product barcode
                                $exchanged_stocked_product_barcode =
                                    StockedProductBarcode::where('bar_code', trim($exchanged_bar_codes[$key]))->first();
                                $exchanged_stocked_product_barcode->sale_detail_id = $returned_sale_detail->id;
                                $exchanged_stocked_product_barcode->save();


                                //update return quantity and exchnaged quantity
                                $productPool =
                                    $sold_stocked_product_barcode->saleDetail->productPoolDetails->productPool;
                                $productPool->return_quantity =
                                    !empty($productPool->return_quantity) ? $productPool->return_quantity + 1 : 1;
                                $productPool->exchange_quantity =
                                    !empty($productPool->exchange_quantity) ? $productPool->exchange_quantity + 1 : 1;
                                $productPool->updated_at = Carbon::now();
                                $productPool->updated_by = Auth::id();
                                $productPool->save();

                                //update return quantity and exchnaged quantity in stock detail pool
                                $productPoolStockDetail =
                                    $sold_stocked_product_barcode->productStockDetailFromBarcode->productPoolDetails->first();
                                $productPoolStockDetail->return_quantity =
                                    !empty($productPoolStockDetail->return_quantity) ? $productPoolStockDetail->return_quantity + 1 : 1;
                                $productPoolStockDetail->exchange_quantity =
                                    !empty($productPoolStockDetail->exchange_quantity) ? $productPoolStockDetail->exchange_quantity + 1 : 1;
                                $productPoolStockDetail->save();


                                //create new product pool sale detail
                                $productPoolSaleDetail = new ProductPoolSaleDetail();
                                $productPoolSaleDetail->sale_detail_id = $returned_sale_detail->id;
                                $productPoolSaleDetail->product_pool_id = $productPool->id;
                                $productPoolSaleDetail->save();

                                //create new product pool product return detail
                                $productPoolProductReturn = new ProductPoolProductReturn();
                                $productPoolProductReturn->product_return_id = $product_return->id;
                                $productPoolProductReturn->product_pool_id = $productPool->id;
                                $productPoolProductReturn->save();

                                // update stock status in original stock detail, product pool, product pool stock detail
                                if ($productPoolStockDetail->available_quantity <= 0) {
                                    $stock_detail =
                                        $sold_stocked_product_barcode->productStockDetailFromBarcode->first();
                                    $stock_detail->status = 'stock_out';
                                    $stock_detail->save();

                                    $productPoolStockDetail->stock_status = 'stock_out';
                                    $productPoolStockDetail->save();
                                }
                                if ($productPool->available_quantity <= 0) {
                                    $productPool->stock_status = 'stock_out';
                                    $productPool->save();
                                }


                                //update exchange product barcode in product_return
                                $product_return->exchanged_stocked_product_barcode_id =
                                    $exchanged_stocked_product_barcode->id;
                                $product_return->exchanged_stocked_product_barcode =
                                    trim($exchanged_stocked_product_barcode->bar_code);
                                $product_return->approved_request_type = trim($request->request_type);
                                $product_return->save();
                            } else {
                                return back()->with('error', 'Stock Unavailable');
                            }
                        }
                    }
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception);
            return back()->with('error', 'Failed to process');
        }
        DB::commit();
        return redirect()->back()->with('success', 'Product Return request submitted');
    }

    public function getExchangedProductBarcodeList(Request $request) {
        if (!request()->ajax()) {
            abort(404);
        }
        $products_bar_code = array();
        $return_array = array();
        if (!empty($request->text)) {

            foreach ($request->text as $key => $bar_code) {
                //if(!empty($request->id)){
                if(!empty($bar_code)){
                    //$existing_product_return = ProductReturn::where('returned_stocked_product_barcode', trim($request->text))->where('id', '!=', $request->id)->first();
                //}else{
                    $existing_product_return = ProductReturn::where('returned_stocked_product_barcode', trim($bar_code))->orWhere('exchanged_stocked_product_barcode', trim($bar_code))->first();
                }
                if($existing_product_return){
                    if($existing_product_return->exchanged_stocked_product_barcode == trim($bar_code)){
                        return response()->json(['code' => 0, 'error' => 'This product already given as exchnaged product']);
                    }
                    if ($existing_product_return->status == 'requested') {
                        return back()->with('error', 'This product already have pending ' . $existing_product_return->request_type . ' request');
                    } elseif($existing_product_return->status == 'approved') {
                        return back()->with('error', 'This product already have approved ' . $existing_product_return->approved_request_type . ' request');
                    } elseif($existing_product_return->status == 'rejected') {
                        return back()->with('error', 'This product already have rejected ' . $existing_product_return->request_type . ' request');
                    }
                }
                $stocked_product = StockedProductBarcode::where('bar_code', $bar_code)->first();
                $products_bar_code[$stocked_product->product_id][] = $stocked_product->bar_code;
            }


            if (!empty($products_bar_code)) {
                foreach ($products_bar_code as $product_id => $value) {
                    $stocked_products = StockedProductBarcode::with('productFromBarcode')
                        ->where('product_id', $product_id)
                        ->where('vendor_id', auth()->user()->vendor_id)
                        ->where(function ($query) {
                            $query->where('sale_detail_id', 0);
                            $query->orWhereNull('sale_detail_id');
                        })
                        ->where(function ($query) {
                            $query->where('product_return_id', 0);
                            $query->orWhereNull('product_return_id');
                        })
                        ->limit(count($value))->get();

                    if(!empty($stocked_products)){
                        foreach($stocked_products as $k => $v){
                            $return_array[$product_id]['product_name'] = $v->productFromBarcode->name;
                            $return_array[$product_id]['data'][$k]['id'] = $v->id;
                            $return_array[$product_id]['data'][$k]['bar_code'] = $v->bar_code;
                            $return_array[$product_id]['data'][$k]['product_id'] = $v->product_id;
                            $return_array[$product_id]['data'][$k]['product_name'] = $v->productFromBarcode->name;
                        }
                    }
                }
            }
        }

        return view('product_returns.get_exchanged_products',compact('return_array'));
    }
}
