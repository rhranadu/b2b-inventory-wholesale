<?php

namespace App\Http\Controllers;

use App\SupplierAccount;
use App\Vendor;
use App\Product;
use App\ProductAttribute;
use App\ProductStock;
use App\Purchase;
use App\PurchaseDetail;
use App\StockDetail;
use App\StockedProductBarcode;
use App\SupplierPaymentTransaction;
use App\Warehouse;
use App\WarehouseDetail;
use App\ProductPoolStockDetail;
use App\ProductPool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;


class ProductStockController extends Controller
{

    public function index()
    {
        $title = 'Stock Details';
        $page_detail = 'Stock Details for your vendor';
        return view('product_stocks.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_stocks = StockDetail::with('product', 'warehouse')
            ->where(['vendor_id' => Auth::user()->vendor_id])
            ->where('status', '!=' ,'die')
            ->get();
        foreach ($product_stocks as &$value){
            $p_p_s_ds = ProductPoolStockDetail::where('stock_detail_id', $value->id)->get();
            $available_quantity = 0;
            foreach ($p_p_s_ds as $p_p_s_d) {
                $available_quantity += $p_p_s_d->available_quantity;
            }
            $value->available_quantity = $available_quantity;
        }
        return Datatables::of($product_stocks)
            ->addIndexColumn()
            ->make(true);

    }

    public function purchasesSubmitToStock(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $quantity = $request->quantity;
        if ($request->filterBarcode == null ) {
            for ($i=1; $i <= $quantity; $i++ ) {
//                unique_barcode_create:{
//                    $current_timestamp = Carbon::now()->timestamp;
//                    $barcode = 'BC'.$current_timestamp.$i;
                $barcode = 'BC'.randGen(10).$i;
//                    if ($barcode) {
//                        $barcodeUnique = StockedProductBarcode::where('bar_code', $barcode)->first();
//                        if( $barcodeUnique){
//                            goto unique_barcode_create;
//                        }else{
                            $barcodes [] = $barcode;
                            $barcode_array = $barcodes;
//                        }
//                    }
//                }
            }
        }
        else {
            $barcode_array = $request->filterBarcode;
        }

        $purchase_detail_id = $request->item_id;
        $purchases_id = $request->purchases_id;
        $warehouse_id = $request->warehouse_id;
        $warehouse_detail_id = $request->warehouse_detail_id;
        $vendor_id = Auth::user()->vendor_id;

        $price = $request->price;
        $total = $request->total;
        //end==> get some request value

        //start==> check purchases and vendor_id and purchases_detail
        $check_purchases = Purchase::where(['id' => $purchases_id, 'vendor_id' => $vendor_id])->first();
        $check_purchasesDetail = PurchaseDetail::with(['productPoolDetails'=>function($q){
            $q->with(['productPool']);
        }])->where(['id' => $purchase_detail_id, 'vendor_id' => $vendor_id])->first();
        $unique_purchase_detail_id = $check_purchasesDetail->id;

        if (empty($check_purchases)) {
            return response()->json('not found');
        }
        if (empty($check_purchasesDetail)) {
            return response()->json('not found');
        }
        //end==> check purchases and vendor_id and purchases_detail

        // Start Barcode Checking
        $check_new_barcodes = StockedProductBarcode::select('bar_code')
            ->where([
                ['vendor_id', '=', $vendor_id],
                ['product_id', '=', $check_purchasesDetail->product_id],
                ['purchase_detail_id', '=', $unique_purchase_detail_id],
            ])
            ->whereIn('bar_code', $barcode_array)
            ->get();
        // End of barcode Checking
        if (count($check_new_barcodes) > 0 ) {
            return response()->json(['barcode_exist' => $check_new_barcodes]);
        }


        //start==> at first we need to check status for this purchases product
        if ($check_purchasesDetail->status == 'DC') {
            return response()->json('NotAllowToStock');
        } elseif ($check_purchasesDetail->status == 'FR') {
            return response()->json('NotAllowToStock');
        }
        //end==> at first we need to check status for this purchases product

        //start===> check purchases quantity and request quantity same or not
        if ($check_purchasesDetail->quantity < $quantity) {
            return response()->json('input_quantity_up');
        }
        //end===> check purchases quantity and store quantity same or not


        // just initialize
        $stock_id = 0;
        $check_product_stock_already_exist = ProductStock::where([
            'purchase_id' => $check_purchases->id,
            'vendor_id' => $vendor_id,
        ])->first();


        if ($check_product_stock_already_exist) {
            $stock_id = $check_product_stock_already_exist->id;

            //start===> check quantity up to stock or not
            $stockedQuantity = StockDetail::where([
                    'product_stock_id' => $check_product_stock_already_exist->id,
                    'purchase_detail_id' => $check_purchasesDetail->id,
                    'product_id' => $check_purchasesDetail->product_id,
                ])->sum('quantity') + $quantity;
            if ($check_purchasesDetail->quantity < $stockedQuantity) {
                return response()->json('quantity_up_to_stock');
            }
            // dump($check_purchasesDetail);
            // dump($stockedQuantity);
            //end===> check quantity up to stock or not


            //start==> we need to add every single product depends same condition based on price

            $checkStockConditionProduct = StockDetail::where([
                'product_stock_id' => $check_product_stock_already_exist->id,
                'warehouse_id' => $warehouse_id,
                'warehouse_detail_id' => $warehouse_detail_id,
                'purchase_detail_id' => $check_purchasesDetail->id,
                'product_id' => $check_purchasesDetail->product_id,
                'price' => $price,
            ])->first();
// dd($checkStockConditionProduct);
            if ($checkStockConditionProduct) {
                $oldQuantity = $checkStockConditionProduct->quantity;
                $oldTotal = $checkStockConditionProduct->total_price;

                DB::transaction(function () use ($checkStockConditionProduct,$oldQuantity,$oldTotal,$quantity,$total,
                    $vendor_id,$unique_purchase_detail_id,$check_purchasesDetail,$barcode_array, $warehouse_id){

                    $checkStockConditionProduct->update([
                        'quantity' => ($oldQuantity + $quantity),
                        'total_price' => ($oldTotal + $total)
                    ]);
                    $poolDetail = ProductPoolStockDetail::with(['productPool'])->with('stockDetail')->where('stock_detail_id', $checkStockConditionProduct->id)->first();
                    $poolDetail->productPool->stock_quantity = ($poolDetail->productPool->stock_quantity + $quantity);

                    $poolDetail->productPool->updated_by = auth()->user()->id;
                    $poolDetail->productPool->updated_at = Carbon::now();
                    $poolDetail->productPool->save();

                    $poolDetail->stock_quantity = ($poolDetail->stock_quantity + $quantity);
                    $poolDetail->save();

                    if ($poolDetail->available_quantity > 0) {
                        $poolDetail->stock_status = null;
                        $poolDetail->save();

                        $poolDetail->stockDetail->status = null;
                        $poolDetail->stockDetail->save();
                    }

                    if ($poolDetail->productPool->available_quantity > 0) {
                        $poolDetail->productPool->stock_status = null;
                        $poolDetail->productPool->save();

                    }
                    foreach ($barcode_array as $val) {
                        StockedProductBarcode::create([
                            'stock_detail_id' => $checkStockConditionProduct->id,
                            'vendor_id' => $vendor_id,
                            'purchase_detail_id' => $unique_purchase_detail_id,
                            'product_id' => $check_purchasesDetail->product_id,
                            'bar_code' => $val,
                        ]);
                    }


                    // Start Profit ==>
                    $product = Product::where('id',$checkStockConditionProduct->product_id)->where('vendor_id',Auth::user()->vendor_id)->first();
                    if (intval($product->average_purchase_price) == 0){
                        $s_d_quantity = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('quantity');
                        $s_d_price = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('total_price');

                        $average_purchase_price = $s_d_price/$s_d_quantity;
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    else{
                        $p_p_ids = ProductPool::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->pluck('id');
                        $p_p_s_ds = ProductPoolStockDetail::whereIn('product_pool_id',$p_p_ids)->get();

                        $available_quantity = 0;
                        foreach($p_p_s_ds as $p_p_s_d){
                            $available_quantity += $p_p_s_d->available_quantity;
                        }

                        $s_d = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->latest()->first();
                        $total_purchase_price_1 = ($available_quantity - $s_d->quantity)*$product->average_purchase_price;
                        $average_purchase_price = ($total_purchase_price_1 + $s_d->total_price) / ($available_quantity);
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    // End Profit <==

                });
                //end==> we need to add every single product depends same condition

            }
            else {
                DB::transaction(function () use ($check_product_stock_already_exist,$vendor_id,$check_purchasesDetail,
                    $warehouse_id,$warehouse_detail_id,$price,$quantity,$total,$barcode_array,$unique_purchase_detail_id){

                    $new_stock_details_id = StockDetail::create([
                        'product_stock_id' => $check_product_stock_already_exist->id,
                        'vendor_id' => $vendor_id,
                        'purchase_detail_id' => $check_purchasesDetail->id,
                        'product_id' => $check_purchasesDetail->product_id,
                        'warehouse_id' => $warehouse_id,
                        'warehouse_detail_id' => $warehouse_detail_id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total_price' => $total,
                    ]);

                    $pool = $check_purchasesDetail->productPoolDetails->productPool;

                    $poolDetail = new ProductPoolStockDetail();
                    $poolDetail->product_pool_id = $pool->id;
                    $poolDetail->stock_quantity = $quantity ;
                    $poolDetail->warehouse_id = $warehouse_id ;
                    $poolDetail->warehouse_detail_id = $warehouse_detail_id;
                    $poolDetail->product_pool_mp_order_confirmation_pending  = $pool->mp_order_confirmation_pending;
                    $poolDetail->stock_detail_id = $new_stock_details_id->id;
                    $poolDetail->save();


                    $pool->stock_quantity = $pool->stock_quantity + $quantity;
                    $pool->updated_by = auth()->user()->id;
                    $pool->updated_at = Carbon::now();
                    $pool->save();

                    if ($pool->available_quantity > 0) {
                        $pool->stock_status = null;
                        $pool->save();
                    }
                    foreach ($barcode_array as $val) {
                        StockedProductBarcode::create([
                            'stock_detail_id' => $new_stock_details_id->id,
                            'vendor_id' => $vendor_id,
                            'purchase_detail_id' => $unique_purchase_detail_id,
                            'product_id' => $check_purchasesDetail->product_id,
                            'bar_code' => $val,
                        ]);
                    }

                    // Start Profit ==>
                    $product = Product::where('id',$check_purchasesDetail->product_id)->where('vendor_id',Auth::user()->vendor_id)->first();
                    if (intval($product->average_purchase_price) == 0){
                        $s_d_quantity = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('quantity');
                        $s_d_price = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('total_price');
                        $average_purchase_price = $s_d_price/$s_d_quantity;
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    else{
                        $p_p_ids = ProductPool::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->pluck('id');
                        $p_p_s_ds = ProductPoolStockDetail::whereIn('product_pool_id',$p_p_ids)->get();
                        $available_quantity = 0;

                        foreach($p_p_s_ds as $p_p_s_d){
                            $available_quantity += $p_p_s_d->available_quantity ;
                        }

                        $s_d = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->latest()->first();
                        $total_purchase_price_1 = ($available_quantity - $s_d->quantity)*$product->average_purchase_price;
                        $average_purchase_price = ($total_purchase_price_1 + $s_d->total_price)/($available_quantity);
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    // End Profit <==

                });
            }

            // total quantity
            $getStockedQuantity = StockDetail::where([
                'product_stock_id' => $check_product_stock_already_exist->id,
                'purchase_detail_id' => $check_purchasesDetail->id,
                'product_id' => $check_purchasesDetail->product_id,
            ])->sum('quantity');

        } else {
            // first new create
            DB::transaction(function () use ($purchases_id,$check_purchases,$vendor_id,$check_purchasesDetail,
                $warehouse_id,$warehouse_detail_id,$price,$quantity,$total,$barcode_array,$unique_purchase_detail_id, &$stock_id) {
                $product_stock = ProductStock::create([
                    'purchase_id' => $purchases_id,
                    'vendor_id' => Auth::user()->vendor_id,
                    'supplier_id' => $check_purchases['supplier_id'],
                    'invoice_no' => $check_purchases->invoice_no,
                    'date' => $check_purchases->date,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $stock_id = $product_stock->id;
                $last_stock_details_id = StockDetail::create([
                    'product_stock_id' => $product_stock->id,
                    'vendor_id' => $vendor_id,
                    'purchase_detail_id' => $check_purchasesDetail->id,
                    'product_id' => $check_purchasesDetail->product_id,
                    'warehouse_id' => $warehouse_id,
                    'warehouse_detail_id' => $warehouse_detail_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $total,
                ]);

                $pool = $check_purchasesDetail->productPoolDetails->productPool;
                $pool->stock_quantity = !empty($pool->stock_quantity) ? $pool->stock_quantity + $quantity : $quantity;

                $pool->updated_by = auth()->user()->id;
                $pool->updated_at = Carbon::now();
                $pool->save();

                if ($pool->available_quantity > 0) {
                    $pool->stock_status = null;
                    $pool->save();
                }

                $poolDetail = new ProductPoolStockDetail();
                $poolDetail->product_pool_id = $pool->id;
                $poolDetail->stock_detail_id = $last_stock_details_id->id;
                $poolDetail->stock_quantity = $quantity;
                $poolDetail->warehouse_id = $warehouse_id;
                $poolDetail->warehouse_detail_id = $warehouse_detail_id;
                $poolDetail->product_pool_mp_order_confirmation_pending  = $pool->mp_order_confirmation_pending;
                $poolDetail->save();

                foreach ($barcode_array as $val) {
                    StockedProductBarcode::create([
                        'stock_detail_id' => $last_stock_details_id->id,
                        'vendor_id' => $vendor_id,
                        'purchase_detail_id' => $unique_purchase_detail_id,
                        'product_id' => $check_purchasesDetail->product_id,
                        'bar_code' => $val,
                    ]);
                }
            });
            $getStockedQuantity = $quantity;
            $check_purchases->status = '';
            $check_purchases->save();


            // Start Profit ==>
            $product = Product::where('id',$check_purchasesDetail->product_id)->where('vendor_id',Auth::user()->vendor_id)->first();
            if (intval($product->average_purchase_price) == 0){
                $s_d_quantity = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('quantity');
                $s_d_price = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('total_price');
                $average_purchase_price = $s_d_price/$s_d_quantity;
                $product->average_purchase_price = $average_purchase_price;
                $product->save();
            }
            else{
                $p_p_ids = ProductPool::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->pluck('id');
                $p_p_s_ds = ProductPoolStockDetail::whereIn('product_pool_id',$p_p_ids)->get();

                $available_quantity = 0;
                foreach($p_p_s_ds as $p_p_s_d){
                    $available_quantity += $p_p_s_d->available_quantity ;
                }

                $s_d = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->latest()->first();
                $total_purchase_price_1 = ($available_quantity - $s_d->quantity)*$product->average_purchase_price;
                $average_purchase_price = ($total_purchase_price_1 + $s_d->total_price)/($available_quantity);
                $product->average_purchase_price = $average_purchase_price;
                $product->save();
            }
            // End Profit <==

        }

        //start===> check total quantity of a product based on vendor and insert into products tbl
        $product_latest_quantity = StockDetail::where([
                'product_id' => $check_purchasesDetail->product_id,
            ])->sum('quantity');
        $product_latest_quantity = (int)$product_latest_quantity;
        Product::where([
            'vendor_id' => Auth::user()->vendor_id,
            'id' => $check_purchasesDetail->product_id,
        ])->update(['latest_quantity' => $product_latest_quantity,'updated_by' => Auth::id()]);


        //start===> status change every single purchases product
        if ($getStockedQuantity == $check_purchasesDetail->quantity) {
            $check_purchasesDetail->status = 'FR';
            $check_purchases->status = 'FR';
            $getStatus = 'FR';
        } else {
            $check_purchasesDetail->status = 'PR';
            $check_purchases->status = 'PR';
            $getStatus = 'PR';
        }
        $check_purchases->save();
        $check_purchasesDetail->save();
        //end===> status change every single purchases product
        // now we need to insert barcode for every single stocked product

        //start==> now we are going to payment transaction
        $getProductStock = ProductStock::find($stock_id);
        $getEverySuppliersRows = SupplierPaymentTransaction::where([
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $getProductStock['supplier_id'],
        ])->get();
        $get_total_credit = $getEverySuppliersRows->sum('credit');
        $get_total_debit = $getEverySuppliersRows->sum('debit');

        if ($getEverySuppliersRows->count() > 0) // if existing this vendor info
        {
            $last_record = $getEverySuppliersRows->last(); // get last row info
                if (empty($last_record->debit)) // if the debit is empty for this row
                {
                    if ($get_total_credit > $get_total_debit) // if credit >  debit
                    {
                        $total_balance = $last_record->balance + $total;
                    }else{
                        $total_balance = $total + $last_record->balance;
                    }
                }elseif ($last_record->debit)
                {
                    if ($get_total_credit > $get_total_debit)
                    {
                        $total_balance = ($last_record->balance + $total);
                    }else{
                        if ($last_record->balance)
                        {
                            $total_balance = ($total + $last_record->balance);
                        }else{
                            $total_balance = ($total);
                        }
                    }
                }
            $total_credit =  $total;
        }else{
            $total_balance = $total;
            $total_credit =  $total;
        }
        SupplierPaymentTransaction::create([
            'product_stock_id' => $getProductStock['id'],
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $getProductStock->supplier_id,
            'purchase_id' => $getProductStock->purchase_id,
            'purchase_invoice_no' => $getProductStock->invoice_no,
            'credit' =>  $total_credit,
            'balance' => $total_balance,
            'transaction_date' => $getProductStock->date,
            'particulars' => 'Transaction  of  Purchases [#'.$getProductStock->invoice_no.']',
            'created_by' => Auth::id(),
            'updated_by'=> Auth::id(),
        ]);

        $getSupplierAccount = SupplierAccount::where([
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $getProductStock['supplier_id'],
        ])->first();

        if ($getSupplierAccount){
            SupplierAccount::where([
                'vendor_id' => Auth::user()->vendor_id,
                'supplier_id' => $getProductStock['supplier_id'],
            ])->update(['balance' => $total_balance,'updated_by' => Auth::id()]);

        }else{
            SupplierAccount::create([
                'supplier_id' => $getProductStock->supplier_id,
                'vendor_id' => Auth::user()->vendor_id,
                'balance' => $total_balance,
                'created_by' => Auth::id(),
                //'status' => 1, // temporary set
            ]);
        }


        //end==> now we are going to payment transaction

        return response()->json(['true' => $getStockedQuantity, 'status' => $getStatus]);
    }


    public function ProductDetailsToStockDetails(Request $request)
    {

        if (!$request->ajax())
        {
            abort(404);
        }

        $checkStock = ProductStock::where('purchase_id', $request->purchases_id)->first();
        if ($checkStock) {
            $details = StockDetail::where([
                'product_stock_id' => $checkStock->id,
                'product_id' => $request->product_id,
            ])->get();

            $output = '<tr>
                            <th>Warehouse Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Last stored date</th>
                       </tr>';

            if ($details->count() > 0) {
                foreach ($details as $stock_details) {
                    $output .= '<tr>
                                <td>' . $stock_details->warehouse->name . '</td>
                                <td>' . $stock_details->quantity . '</td>
                                <td>' . $stock_details->price . '</td>
                                <td>' . $stock_details->total . '</td>
                                <td>' . $stock_details->created_at->isoFormat('MMM Do YY') . '</td>
                            </tr>';
                }
            } else {
                $output .= '<tr class="text-center">
                                <td colspan="5" style="color: red">There is no result</td>
                            </tr>';
            }
            return response()->json(['true' => $output]);
        } else {
            return response()->json('false');
        }
    }

    public function barcodeCheck(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_id = $request->product_id;
        $input_barcode_val = $request->input_barcode_val;
        $check_barcode = StockedProductBarcode::select('bar_code')
            ->where([
                ['vendor_id', '=', auth()->user()->vendor_id],
                ['product_id', '=', $product_id],
                ['bar_code', '=', $input_barcode_val],
            ])
            ->first();
        return response()->json(['check_barcodes' => $check_barcode]);

    }

    public function getWarehouseType(Request $request){
        $parent_sections = WarehouseDetail::select('id','parent_section_id','section_name')->where('warehouse_id', $request->warehouse_id)->get();
        $ps = [];
        foreach ($parent_sections as $parent_section){
            if ($parent_section->parent){
                if(isset($ps[$parent_section->parent->id]) && !empty($ps[$parent_section->parent->id])){
                    $ps[$parent_section->id]['id'] = $parent_section->id;
                    $ps[$parent_section->id]['section_name'] = $ps[$parent_section->parent->id]['section_name'] . ' >> ' . $parent_section->section_name;
                }else{
                    $ps[$parent_section->id]['id'] = $parent_section->id;
                    $ps[$parent_section->id]['section_name'] = $parent_section->parent->section_name;
                }
            } else {
                $ps[$parent_section->id]['id'] = $parent_section->id;
                $ps[$parent_section->id]['section_name'] = $parent_section->section_name;
            }
        }
        return response()->json(['parent_sections'=>$ps]);
    }
}
