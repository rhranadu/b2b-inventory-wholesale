<?php

namespace App\Http\Controllers;

use App\ProductStockTransfer;
use App\StockDetail;
use App\Warehouse;
use App\WarehouseDetail;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStockTransfer\ProductStockTransferStore;
use App\ProductPoolStockDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class ProductStockTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Product Stock Transfer';
        $page_detail = 'Product Stock Transfer for your Vendor';
        $warehouses = Warehouse::where('vendor_id', Auth::user()->vendor_id)->get();
        return view('product_stock_transfer.index', compact('warehouses', 'title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $product_stock_transfers = ProductStockTransfer::with('products', 'fromWarehouse', 'toWarehouse')->where('vendor_id', Auth::user()->vendor_id)->latest()->get();
        return Datatables::of($product_stock_transfers)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductStockTransferStore $request)
    {
        $request['created_by'] =  auth()->user()->id;
        $request['updated_by'] =  auth()->user()->id;
        DB::beginTransaction();
        try{
            $product_stock_details = StockDetail::with('productPoolDetails')->where(['id' => $request->stock_details_id])->first();

            if ($request->quantity > $product_stock_details->productPoolDetails->available_quantity) {
                return redirect()->back()->with('error', 'Given quantity is greater than the stock quantity!');
            } else {
                $poolStockDetail = $product_stock_details->productPoolDetails;
                $poolStockDetail->stock_transfer_quantity = intval($poolStockDetail->stock_transfer_quantity) + $request->quantity;
                $poolStockDetail->save();
            
                
                $newStockDetail = new StockDetail();
                $newStockDetail->product_id = $product_stock_details->product_id;
                $newStockDetail->vendor_id = Auth::user()->vendor_id;
                $newStockDetail->product_stock_id = $product_stock_details->product_stock_id;
                $newStockDetail->purchase_detail_id = $product_stock_details->purchase_detail_id;
                $newStockDetail->warehouse_id = $request->to_warehouse_id;
                if (!empty($request->to_warehouse_detail_id)) {
                    $newStockDetail->warehouse_detail_id = $request->to_warehouse_detail_id;
                }
                $newStockDetail->quantity = $request->quantity;
                $newStockDetail->price = $product_stock_details->price;
                $newStockDetail->total_price = $product_stock_details->total_price;
                $newStockDetail->status = 'available';
                $newStockDetail->created_by = Auth::user()->id;
                $newStockDetail->created_at = Carbon::now();
                $newStockDetail->save();
            
                $newPoolStockDetail = new ProductPoolStockDetail();
                $newPoolStockDetail->product_pool_id = $poolStockDetail->product_pool_id;
                $newPoolStockDetail->stock_detail_id = $newStockDetail->id;
                $newPoolStockDetail->warehouse_id = $request->to_warehouse_id;
                if (!empty($request->to_warehouse_detail_id)) {
                    $newPoolStockDetail->warehouse_detail_id = $request->to_warehouse_detail_id;
                }
                $newPoolStockDetail->stock_quantity = $request->quantity;
                $newPoolStockDetail->stock_status = 'available';
                $newPoolStockDetail->save();
            
                $productStockTransfer = new ProductStockTransfer();
                $productStockTransfer->product_id = $product_stock_details->product_id;
                $productStockTransfer->vendor_id = Auth::user()->vendor_id;
                $productStockTransfer->stock_detail_id = $newStockDetail->id;
                $productStockTransfer->from_warehouse_id = $request->from_warehouse_id;
                $productStockTransfer->from_warehouse_detail_id = $request->from_warehouse_detail_id;
                $productStockTransfer->to_warehouse_id = $request->to_warehouse_id;
                $productStockTransfer->to_warehouse_detail_id = $request->to_warehouse_detail_id;
                $productStockTransfer->delivery_quantity = $request->quantity;
                $productStockTransfer->receive_quantity = $request->quantity;
                $productStockTransfer->price = $newStockDetail->price;
                $productStockTransfer->total = $newStockDetail->total_price;
                $productStockTransfer->status = 'transferred';
                $productStockTransfer->memo_no = $request->memo_no;
                $productStockTransfer->created_by = Auth::user()->id;
                $productStockTransfer->created_at = Carbon::now();
                $productStockTransfer->save();
            }
            
        } catch (\Exception $exc) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
        DB::commit();
        return redirect()->back()->with('success', 'Product Stock Transfer Successfull!');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getWarehouseType($id = null, $warehouseDetailId = null)
    {
        $warehouse = Warehouse::where('id', $id)->value('type');

        $to_warehouses = Warehouse::where([
            'vendor_id' => Auth::user()->vendor_id,
        ])->where('id', '<>', $id)->get();

        $products = StockDetail::with('productPoolDetails')->where([
            'vendor_id' => Auth::user()->vendor_id,
            'warehouse_id' => $id,
        ]);
        if (!empty($warehouseDetailId)) {
            $products->where('warehouse_detail_id', $warehouseDetailId);
        }
        $products = $products->get();
        $pd = [];
        foreach ($products as $product) {
            if ($product->productPoolDetails->available_quantity > 0) {
                $d['id'] = $product->id;
                $d['product_name'] = $product->product->name . ' >> ' . $product->productPoolDetails->available_quantity;
                $pd[] = $d;
            }
        }
        $parent_sections = WarehouseDetail::select('id', 'parent_section_id', 'section_name')->where('warehouse_id', $id)->get();
        $ps = [];
        foreach ($parent_sections as $parent_section) {
            if ($parent_section->parent) {
                if (isset($ps[$parent_section->parent->id]) && !empty($ps[$parent_section->parent->id])) {
                    $ps[$parent_section->id]['id'] = $parent_section->id;
                    $ps[$parent_section->id]['section_name'] = $ps[$parent_section->parent->id]['section_name'] . ' >> ' . $parent_section->section_name;
                } else {
                    $ps[$parent_section->id]['id'] = $parent_section->id;
                    $ps[$parent_section->id]['section_name'] = $parent_section->parent->section_name;
                }
            } else {
                $ps[$parent_section->id]['id'] = $parent_section->id;
                $ps[$parent_section->id]['section_name'] = $parent_section->section_name;
            }
        }
        return response()->json([
            'warehouse' => $warehouse, 'parent_sections' => $ps,
            'to_warehouses' => $to_warehouses, 'products' => $pd
        ]);
    }
}
