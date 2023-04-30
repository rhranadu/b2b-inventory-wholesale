<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\PosCustomer;
use App\Product;
use App\Sale;
use App\SaleDetail;
use App\SaleOrder;
use App\SalePayment;
use App\StockDetail;
use App\StockedProductBarcode;
use App\ProductAttribute;
use App\ProductAttributeMap;
use App\ProductPoolSaleDetail;
use App\ProductPool;
use App\SaleAttributeDetails;
use App\SaleCommission;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Integer;
use Carbon\Carbon;

class SaleController extends Controller
{

    //deprecated
    public function index()
    {

        if (Auth::user()->user_type_id == 2) {
            $sales = Sale::with(['saleDetails'])->where(['vendor_id' => auth()->user()->vendor_id])->get();
        } else {
            $sales = Sale::with(['saleDetails' => function ($query) {
                $query->where('warehouse_id', Auth::user()->warehouse_id);
            }])->where(['vendor_id' => auth()->user()->vendor_id])
                ->where('user_warehouse_id', Auth::user()->warehouse_id)
                ->get();
        }
        return view('sales.index', compact('sales'));
    }

    //deprecated
    public function create()
    {
        if (Auth::user()->user_type_id == 2) {
            $last = PosCustomer::where('vendor_id', auth()->user()->vendor_id)->latest()->first();
            $poscustomers = PosCustomer::where('vendor_id', auth()->user()->vendor_id)->get(['id', 'name']);
            $products = StockDetail::where(['vendor_id' => auth()->user()->vendor_id])
                ->where('quantity', '!=', 0)->get();
        } else {
            $last = PosCustomer::where(['vendor_id' => auth()->user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id])->latest()->first();
            $poscustomers = PosCustomer::where(['vendor_id' => auth()->user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id])->get(['id', 'name']);
            $products = StockDetail::where(['vendor_id' => auth()->user()->vendor_id])
                ->where('warehouse_id', Auth::user()->warehouse_id)
                ->where('quantity', '!=', 0)->get();
        }

        return view('sales.create', compact('poscustomers', 'products', 'last'));
    }

    //deprecated
    // public function getsingleProductDetails(Request $request)
    // {
    //     if (!\request()->ajax()) {
    //         abort(404);
    //     }
    //     $product_id = $request->product_id;
    //     $product = Product::active()->where(['vendor_id' => auth()->user()->vendor_id, 'id' => $product_id])->first();
    //     if ($product) {
    //         $stocked_products = StockDetail::with(['productPoolDetails'])->where('product_id', $product_id)->get();

    //         $output = '';
    //         if ($stocked_products->count() > 0) {
    //             foreach ($stocked_products as $product) {
    //                 $output .= '<div class="col-md-3 col-sm-6">
    //                         <div class="product-grid4">
    //                             <div class="product-image4">
    //                               <img class="pic-1" src="' . $product->image_path . '">
    //                                 <span class="product-discount-label">' . $product->pos_discount_price . '</span>
    //                             </div>
    //                             <div class="product-content">
    //                                 <h3 class="title">' . $product->product->name . '</h3>
    //                                 <h3 class="title"><b>Brand:</b> ' . $product->product->productBrand->name . '</h3>
    //                                 <h3 class="title"><b>Category:</b> ' . $product->product->productCategory->name . '</h3>
    //                                 <h3 class="title"><b>Attribute:</b> ' . $product->productAttribute->name . ' - ' . $product->productAttributeMap->value . '</h3>

    //                                 <div class="price">
    //                                    Price:  ' . (!empty($product->product->pos_discount_price) ? $product->product->pos_discount_price : $product->product->max_price ). '
    //                                 </div>
    //                                 <button onclick="getSingleProductDetails(this)"   data-product_attribute_map_name="' . $product->productAttributeMap->value . '"  data-product_attribute_name="' . $product->productAttribute->name . '"  data-product_name="' . $product->product->name . '"  data-quantity="' . intval($product->productPoolDetails->stock_quantity) - intval($product->productPoolDetails->product_pool_mp_order_confirmation_pending) - intval($product->productPoolDetails->pos_order_submitted_quantity) . '" data-max_price="' . (!empty($product->product->pos_discount_price) ? $product->product->pos_discount_price : $product->product->max_price ) . '" data-product_attribute_map_id="' . $product->product_attribute_map_id . '" data-product_id="' . $product->id . '" data-product_attribute_id="' . $product->product_attribute_id . '" data-warehouse_id="' . $product->warehouse_id . '" type="button" class="add-to-cart" href="">ADD</button>
    //                             </div>
    //                         </div>
    //                     </div>';
    //             }
    //         } else {
    //             $output .= '<p class="text-center text-danger">Not found</p>';
    //         }
    //         return response()->json(['true' => $output]);

    //     }
    // }

    // for use as marketplace sale (test use only)

    // public function saleProductStore(Request $request)
    // {
    //     if (!\request()->ajax()) {
    //         abort(404);
    //     }
    //     $pos_customer_id = $request->pos_customer_id;
    //     $vendor_id = auth()->user()->vendor_id;
    //     $product_id_arr = $request->product;
    //     $attribute_id_arr = $request->attribute;
    //     $attribute_map_id_arr = $request->attribute_map;
    //     $quantity_arr = $request->quantity;
    //     $per_price_arr = $request->per_price;
    //     $total_arr = $request->total;
    //     $sub_total = $request->sub_total;
    //     $tax = $request->tax;
    //     $shipping_charge = $request->shipping_charge;
    //     $discount = $request->discount;
    //     $final_total = $request->final_total;
    //     //end==> first check quantity is available or not
    //     DB::beginTransaction();
    //     try {
    //         $in_no = Str::random(5);
    //         $unique_invoice_no = Sale::where(['invoice_no' => $in_no, 'vendor_id' => $vendor_id,])->first();
    //         if ($unique_invoice_no) {
    //             $in_no .= Str::random(2);
    //         }
    //         $pool_id_arr = [0 => "1", 1 => "2"];
    //         $sale = Sale::create([
    //             'vendor_id' => $vendor_id,
    //             'pos_customer_id' => $pos_customer_id,
    //             'type_of_sale' => 2,
    //             'invoice_no' => $in_no,
    //             'items' => count($product_id_arr),
    //             'sub_total' => $sub_total,
    //             'tax' => $tax,
    //             'shipping_charge' => $shipping_charge,
    //             'discount' => $discount,
    //             'final_total' => $final_total,
    //             'created_by' => auth()->id(),
    //             'updated_by' => auth()->id(),
    //         ]);
    //         foreach ($pool_id_arr as $key => $value) // start foreach loop
    //         {
    //             // check quantity
    //             // $productStockDetail = StockDetail::with(['productPoolDetails' => function($q){
    //             //     $q->with('productPool');
    //             // }])->whereIn('id',[1,2,3,4,5,6])->get();
    //             $product_pool = ProductPool::with(['stockDetails' => function($q){
    //                 $q->with('stockDetail');
    //             }])->where('id',$value)->first();

    //             if ($product_pool->available_quantity >= $quantity_arr[$key]) {
    //                 $sale_detail = SaleDetail::create([
    //                     'sale_id' => $sale->id,
    //                     'vendor_id' => $vendor_id,
    //                     'product_id' => $product_pool->product_id,
    //                     'quantity' => $quantity_arr[$key],
    //                     'per_price' => $per_price_arr[$key],
    //                     'total' => $total_arr[$key],
    //                 ]);

    //                 $this_attribute_id_arr = explode(',', $attribute_id_arr[$key]);
    //                 $this_attribute_map_id_arr = explode(',', $attribute_map_id_arr[$key]);
    //                 foreach ($this_attribute_id_arr as $k => $v) {
    //                     $attribute_info = ProductAttribute::where('id', $v)->select('name')->first();
    //                     $attribute_maps_info = ProductAttributeMap::where('id', $this_attribute_map_id_arr[$k])->select('value')->first();
    //                     SaleAttributeDetails::create([
    //                         'sale_detail_id' => $sale_detail->id,
    //                         'product_attribute_id' => $v,
    //                         'product_attribute_name' => $attribute_info['name'],
    //                         'product_attribute_map_id' => $this_attribute_map_id_arr[$k],
    //                         'product_attribute_map_name' => $attribute_maps_info['value'],
    //                         'created_at' => date('Y-m-d H:i:s'),
    //                         'updated_at' => date('Y-m-d H:i:s'),
    //                     ]);
    //                 }
    //                 SaleOrder::create([
    //                     'sale_detail_id' => $sale_detail->id,
    //                     'status' => 'submitted',
    //                     'submitted_at' => date('Y-m-d H:i:s'),
    //                     'created_by' => Auth::user()->id,
    //                     'updated_by' => Auth::user()->id,
    //                 ]);
    //                 if ($product_pool->available_quantity - $quantity_arr[$key] === 0) {
    //                     $product_pool->stock_status = 'stock_out';
    //                 }
    //                 $product_pool->mp_order_submitted_quantity  = !empty($product_pool->mp_order_submitted_quantity) ?  $product_pool->mp_order_submitted_quantity + $quantity_arr[$key] : $quantity_arr[$key];
    //                 $product_pool->mp_order_confirmation_pending  = !empty($product_pool->mp_order_confirmation_pending) ?  $product_pool->mp_order_confirmation_pending + $quantity_arr[$key] : $quantity_arr[$key];
    //                 $product_pool->updated_by = auth()->user()->id;
    //                 $product_pool->updated_at = Carbon::now();
    //                 $product_pool->save();

    //                 $saleDetailPool = new ProductPoolSaleDetail();
    //                 $saleDetailPool->sale_detail_id = $sale_detail->id;
    //                 $saleDetailPool->product_pool_id = $product_pool->id;
    //                 $saleDetailPool->save();
    //             }
    //         } // end foreach loop
    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         return response()->json(['failed' => $exception->getMessage()]);
    //     }
    //     DB::commit();
    //     return response()->json(['success' => $sale->id]);
    // }

    public function saleProductStore(Request $request)
    {
        if (!\request()->ajax()) {
            abort(404);
        }

        $pos_customer_id = $request->pos_customer_id;
        $vendor_id = auth()->user()->vendor_id;
        $stock_detail_id_arr = $request->stock_detail_id;
        $stocked_product_barcode_id_arr = $request->stocked_product_barcode_id;
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
        $total_quantity = array_sum($quantity_arr);
        $per_discount = $discount / $total_quantity;
        $validator = Validator::make($request->all(), [
            'pos_customer_id' => 'required|integer',
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
            'pos_customer_id.required' => 'PosCustomer field is required',
            'pos_customer_id.integer' => 'PosCustomer  field must be an integer',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        } else {

            //start==> first check quantity is available or not
            foreach ($product_id_arr as $key => $value) // start foreach loop
            {
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
            //end==> first check quantity is available or not
            DB::beginTransaction();
            try {
                $vC = auth()->user()->vendor->active_sale_commission;
                $swg = $final_total * ($vC/100);
                $vwg = $final_total - $swg;
                $in_no = Str::random(5);
                $unique_invoice_no = Sale::where(['invoice_no' => $in_no, 'vendor_id' => $vendor_id,])->first();
                if ($unique_invoice_no) {
                    $in_no .= Str::random(2);
                }
                $sale = Sale::create([
                    'vendor_id' => $vendor_id,
                    'pos_customer_id' => $pos_customer_id,
                    'invoice_no' => $in_no,
                    'items' => count($product_id_arr),
                    'sub_total' => $sub_total,
                    'tax' => $tax,
                    'shipping_charge' => $shipping_charge,
                    'discount' => $discount,
                    'discount_percentage' => $discount_percentage,
                    'final_total' => $final_total,
                    'commission_percentage' => $vC,
                    'superadmin_will_get' => $swg,
                    'vendor_will_get' => $vwg,
                    'user_warehouse_id' => Auth::user()->warehouse_id ?? 0,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                SalePayment::create([
                    'sale_id' => $sale->id,
                    'vendor_id' => auth()->user()->vendor_id,
                    'warehouse_id' => auth()->user()->warehouse_id ?? 0,
                    'pos_customer_id' => $pos_customer_id,
                    'final_total' => $final_total,
                    'payment_by' => '',
                    'pay_amount' => 0,
                    'due_amount' => $final_total,
                    'give_back' => 0,
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
                    if ($productStockDetail->productPoolDetails->available_quantity >= $quantity_arr[$key]) {

                        // Start Profit
                        $product = Product::where('id',$value)->where('vendor_id',$vendor_id)->first();
                        $s_d = SaleDetail::where('product_id',$value)->where('vendor_id',$vendor_id)->latest()->first();
                        $total_cumulative_purchase_price = 0;
                        $total_cumulative_sold_price = 0;
                        if (empty($s_d)){
                            $total_cumulative_purchase_price = $quantity_arr[$key] * $product->average_purchase_price;
                            $total_cumulative_sold_price = $quantity_arr[$key] * ($per_price_arr[$key] - $per_discount);
                        }else{
                            $total_cumulative_purchase_price = $quantity_arr[$key] * $product->average_purchase_price;
                            $total_cumulative_sold_price = $quantity_arr[$key] * ($per_price_arr[$key] - $per_discount);
                        }

                        $sale_detail = SaleDetail::create([
                            'sale_id' => $sale->id,
                            'vendor_id' => $vendor_id,
                            'product_id' => $value,
                            'average_purchase_price' => $product->average_purchase_price,
                            'total_cumulative_purchase_price' => $total_cumulative_purchase_price,
                            'total_cumulative_sold_price' => $total_cumulative_sold_price,
                            'total_cumulative_profit' => ($total_cumulative_sold_price - $total_cumulative_purchase_price),
                            'warehouse_id' => $warehouse_id_arr[$key],
                            'quantity' => $quantity_arr[$key],
                            'per_price' => $per_price_arr[$key],
                            'total' => $total_arr[$key],
                        ]);
                        // End Profit

                        $this_attribute_id_arr = explode(',', $attribute_id_arr[$key]);
                        $this_attribute_map_id_arr = explode(',', $attribute_map_id_arr[$key]);
                        foreach ($this_attribute_id_arr as $k => $v) {
                            $attribute_info = ProductAttribute::where('id', $v)->select('name')->first();
                            $attribute_maps_info = ProductAttributeMap::where('id', $this_attribute_map_id_arr[$k])->select('value')->first();
                            SaleAttributeDetails::create([
                                'sale_detail_id' => $sale_detail->id,
                                'product_attribute_id' => $v,
                                'product_attribute_name' => $attribute_info['name'],
                                'product_attribute_map_id' => $this_attribute_map_id_arr[$k],
                                'product_attribute_map_name' => $attribute_maps_info['value'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ]);
                        }

                        SaleOrder::create([
                            'sale_detail_id' => $sale_detail->id,
                            'warehouse_id' => $warehouse_id_arr[$key],
                            'status' => 'delivered',
                            'submitted_at' => date('Y-m-d H:i:s'),
                            'confirmed_at' => date('Y-m-d H:i:s'),
                            'processed_at' => date('Y-m-d H:i:s'),
                            'shipped_at' => date('Y-m-d H:i:s'),
                            'delivered_at' => date('Y-m-d H:i:s'),
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                        ]);


                        $pool = $productStockDetail->productPoolDetails->productPool;
                        $pool->pos_order_submitted_quantity = !empty($pool->pos_order_submitted_quantity) ?  $pool->pos_order_submitted_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $pool->pos_order_confirmed_quantity = !empty($pool->pos_order_confirmed_quantity) ?  $pool->pos_order_confirmed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $pool->pos_order_processed_quantity = !empty($pool->pos_order_processed_quantity) ?  $pool->pos_order_processed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $pool->pos_order_shipped_quantity = !empty($pool->pos_order_shipped_quantity) ?  $pool->pos_order_shipped_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $pool->pos_order_delivered_quantity = !empty($pool->pos_order_delivered_quantity) ?  $pool->pos_order_delivered_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $pool->updated_by = auth()->user()->id;
                        $pool->updated_at = Carbon::now();
                        $pool->save();


                        $stockPoolDetail = $productStockDetail->productPoolDetails;
                        $stockPoolDetail->pos_order_submitted_quantity = !empty($stockPoolDetail->pos_order_submitted_quantity) ?  $stockPoolDetail->pos_order_submitted_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $stockPoolDetail->pos_order_confirmed_quantity = !empty($stockPoolDetail->pos_order_confirmed_quantity) ?  $stockPoolDetail->pos_order_confirmed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $stockPoolDetail->pos_order_processed_quantity = !empty($stockPoolDetail->pos_order_processed_quantity) ?  $stockPoolDetail->pos_order_processed_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $stockPoolDetail->pos_order_shipped_quantity = !empty($stockPoolDetail->pos_order_shipped_quantity) ?  $stockPoolDetail->pos_order_shipped_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $stockPoolDetail->pos_order_delivered_quantity = !empty($stockPoolDetail->pos_order_delivered_quantity) ?  $stockPoolDetail->pos_order_delivered_quantity + $quantity_arr[$key] : $quantity_arr[$key];
                        $stockPoolDetail->save();

                        $saleDetailPool = new ProductPoolSaleDetail();
                        $saleDetailPool->sale_detail_id = $sale_detail->id;
                        $saleDetailPool->product_pool_id = $pool->id;
                        $saleDetailPool->save();

                        // Stocked Product Barcodes Update
                        $stocked_product_barcodess =
                            StockedProductBarcode::where('product_id',$value)->where(
                                            function ($query){
                                                $query->where('sale_detail_id', 0);
                                                $query->orWhereNull('sale_detail_id');
                                            })->limit($quantity_arr[$key])->get();

                        if (!empty($stocked_product_barcodess)) {
                            foreach ($stocked_product_barcodess as $barcode) {
                                if (!empty($barcode)) {
                                    $barcode->update([
                                        'sale_detail_id' => $sale_detail->id
                                    ]);
                                }
                            }
                        }

                        // update stock status in original stock detail, product pool, product pool stock detail
                        if ($stockPoolDetail->available_quantity <= 0) {
                            $productStockDetail->status = 'stock_out';
                            $productStockDetail->save();

                            $stockPoolDetail->stock_status = 'stock_out';
                            $stockPoolDetail->save();
                        }
                        if ($pool->available_quantity <= 0) {
                            $pool->stock_status = 'stock_out';
                            $pool->save();
                        }
                    }
                } // end foreach loop
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json(['failed' => $exception->getMessage()]);
            }
            DB::commit();
            return response()->json(['success' => $sale->id]);
        }

    }


    public function SalePaymentStore(Request $request, Sale $sale)
    {

        if (Auth::user()->vendor_id != $sale->vendor_id) {
            return redirect()->back()->with('warning', 'Wrong url');
        }

        $last_sale_id = $sale->id;
        $pos_customer_id = $request->customer_id;
        $card_name = $request->card_name;
        $card_number = $request->card_number;
        $check_no = $request->check_no;
        $now_payment_amount = $request->pay_money;
        $already_paid = $request->already_pay;
        $payment_type = $request->payment_type;
        $final_total = $request->final_total;
        $due = $request->due_money;
        $give_back = $request->give_back ?? 0;
        $status = $final_total <= ($now_payment_amount + $already_paid) ? 'FP' : 'PP';

        $request->validate([
            'pay_money' => 'required'
        ]);

        if ($last_sale_id) {
            SalePayment::create([
                'sale_id' => $last_sale_id,
                'vendor_id' => auth()->user()->vendor_id,
                'warehouse_id' => auth()->user()->warehouse_id ?? 0,
                'pos_customer_id' => $pos_customer_id,
                'final_total' => $final_total,
                'payment_by' => $payment_type,
                'check_no' => $check_no,
                'card_name' => $card_name,
                'card_number' => $card_number,
                'pay_amount' => $now_payment_amount,
                'due_amount' => $due,
                'give_back' => $give_back,
                'status' => $status,
            ]);
            $sale->status = $status;
            $sale->save();
            return redirect()->back()->with('success', 'Pay Success !');
        }

    }


    public function SalePaymentWithAjax(Request $request)
    {

        if (!\request()->ajax()) {
            abort(404);
        }
        $last_sale_id = $request->last_sale_id;
        $pos_customer_id = $request->pos_customer_id;
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
            'vendor_id' => auth()->user()->vendor_id])->first();
        if ($sale_check) {
            $salePayment = new SalePayment();
            $salePayment->sale_id = $last_sale_id;
            $salePayment->vendor_id = auth()->user()->vendor_id;
            $salePayment->warehouse_id = auth()->user()->warehouse_id ?? 0;
            $salePayment->pos_customer_id = $pos_customer_id;
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


    public function SalePayment(Sale $sale)
    {

        $sale_info = $sale;
        if (Auth::user()->user_type_id == 2) {
            if (Auth::user()->vendor_id == $sale_info->vendor_id) {
                return view('sales.payment', compact('sale_info'));
            }
        } elseif (Auth::user()->user_type_id == 0) {
            if (Auth::user()->vendor_id == $sale_info->vendor_id && (Auth::user()->warehouse_id == $sale_info->user_warehouse_id)) {
                return view('sales.payment', compact('sale_info'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }

    }


    public function SaleDetail(Sale $sale)
    {

        $sale_info = $sale;
        if (Auth::user()->user_type_id == 2 && Auth::user()->vendor_id == $sale_info->vendor_id) {
            return view('sales.details', compact('sale_info'));
        } elseif (Auth::user()->user_type_id == 0) {
            if (Auth::user()->vendor_id == $sale_info->vendor_id && (Auth::user()->warehouse_id == $sale_info->user_warehouse_id)) {
                return view('sales.details', compact('sale_info'));
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    // deprecated
    public function searchProduct(Request $request)
    {
        if (!\request()->ajax()) {
            abort(404);
        }
        if (Auth::user()->user_type_id == 2) {
            $products = Product::has('productStockes')->with(['productStockes' => function ($query) {
                $query->where('quantity', '!=', 0);
            }])
                ->where('name', 'LIKE', '%' . $request->text . '%')
                ->where('vendor_id', auth()->user()->vendor_id)
                ->get();

            if ($products->count() > 0) {
                Session::forget('search_with_barcode');
                return view('sales.product_search', compact('products'));
            } else {
                Session::put('search_with_barcode', true);
                $products = StockedProductBarcode::has('productStockDetailFromBarcode')->with(['productStockDetailFromBarcode' => function ($query) {
                    $query->where('quantity', '!=', 0);
                    $query->where('vendor_id', Auth::user()->vendor_id);
                }])
                    ->where('bar_code', $request->text)
                    ->where('vendor_id', auth()->user()->vendor_id)
                    ->get();
                return view('sales.product_search', compact('products'));
            }

        } else {
            // only for warehouses access's man
            $products = Product::has('productStockes')->with(['productStockes' => function ($query) {
                $query->where('quantity', '!=', 0)
                    ->where('warehouse_id', Auth::user()->warehouse_id);
            }])
                ->where('name', 'LIKE', '%' . $request->text . '%')
                ->where('vendor_id', auth()->user()->vendor_id)
                ->get();

            if ($products->count() > 0) {
                Session::forget('search_with_barcode');
                return view('sales.product_search', compact('products'));
            } else {
                Session::put('search_with_barcode', true);
                $products = StockedProductBarcode::has('productStockDetailFromBarcode')->with(['productStockDetailFromBarcode' => function ($query) {
                    $query->where('quantity', '!=', 0);
                    $query->where('vendor_id', Auth::user()->vendor_id);
                    $query->where('warehouse_id', Auth::user()->warehouse_id);
                }])
                    ->where('bar_code', $request->text)
                    ->where('vendor_id', auth()->user()->vendor_id)
                    ->get();
                return view('sales.product_search', compact('products'));
            }
        }
    }


    public function customerInfo(Request $request)
    {
        if (!\request()->ajax()) {
            abort(404);
        }
        $customer = PosCustomer::where(['vendor_id' => auth()->user()->vendor_id, 'id' => $request->id])->first();
        if ($customer) {
            $html = '<div class="col-lg-12 col-md-6 col-sm-6 col-xs-12" style="margin-top: 25px;">
                        <div class="invoice-cmp-ds">
                            <div class="comp-tl">
                                <h2>' . $customer->name . '</h2>
                                <p>' . $customer->email . '</p>
                                <p>' . $customer->phone . '</p>
                                <p>' . $customer->address . '</p>
                            </div>
                        </div>
                   </div>';
            return $html;
        } else {
            return response()->json('false');
        }
    }
    //deprecated
    public function pos()
    {
        $products = Product::where('products.status', 1)
            ->where('products.vendor_id', Auth::guard('vendor')->id())
            ->inRandomOrder()
            ->get()
            ->where('current_stock', '>', 0)
            ->take(35);
        return view('pos.index', compact('products'));
    }

    public function point_of_sale()
    {
        return view('pos.index-bak');
    }

}
