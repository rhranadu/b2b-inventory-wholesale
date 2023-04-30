<?php

namespace App\Http\Controllers;

use App\ProductAttributeMap;
use App\ProductPool;
use App\ProductPoolSaleDetail;
use App\ProductPoolStockDetail;
use App\Sale;
use App\SaleAttributeDetails;
use App\SaleDetail;
use App\SaleNegotiation;
use App\SaleOrder;
use App\StockedProductBarcode;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SaleNegotiationController extends Controller
{

    public function index(Request $request)
    {

        if (!$request->ajax()) {
            $title       = 'Order Negotiation';
            $page_detail = 'List of Order Negotiation';
            return view('sale_negotiations.index', compact('title', 'page_detail'));
        }

        $data = SaleNegotiation::with('saleOrder')
            ->with(["productPool" => function ($query) {
                $query->where('vendor_id', Auth::user()->vendor_id);
                $query->with(["stockDetails"]);
            }])
            ->with('product')
            ->where('vendor_id', Auth::user()->vendor_id);
        if (!empty($request->start_date) && empty($request->end_date)) {
            $data->where('created_at', '>=', $request->start_date . ' 00:00:00');
        }
        if (!empty($request->end_date) && empty($request->start_date)) {
            $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        if (!empty($request->end_date) && !empty($request->start_date)) {
            $data->where('created_at', '>=', $request->start_date . ' 00:00:00');
            $data->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        if (!empty($request->order_status)) {
            $data->where('status', '=', trim($request->order_status));
        }

        $datas = $data->get();
        $s_n_ids = array();
        foreach ($datas as $data){
            $s_n_ids[$data->uuid] = $data->id;
        }
        $SNIds = array();
        foreach ($s_n_ids as $id){
            $SNIds[] = $id;
        }
        $data = SaleNegotiation::with('saleOrder')
            ->with(["productPool" => function ($query) {
                $query->with(["stockDetails"]);
            }])
            ->with('product')
            ->whereIn('id', $SNIds)
            ->latest()
            ->get()->toArray();

        $pool = [];
        foreach ($data as $pn) {
            $dt['sale_negotiation_id'] = $pn['id'];
            $dt['product_name']        = $pn['product']['name'];
            $dt['attribute_pair']      = $pn['product_pool']['attribute_pair_text'];
            $dt['original_price']      = $pn['original_price'];

            $dt['client_asking_price']    = $pn['client_asking_price'];
            $dt['client_asking_quantity'] = $pn['client_asking_quantity'];
            $dt['client_asking_total']    = $pn['client_asking_total'];
            $dt['client_remarks']         = $pn['client_remarks'];

            $dt['vendor_asking_price']    = $pn['vendor_asking_price'];
            $dt['vendor_asking_quantity'] = $pn['vendor_asking_quantity'];
            $dt['vendor_asking_total']    = $pn['vendor_asking_total'];
            $dt['vendor_remarks']         = $pn['vendor_remarks'];

            $dt['available_quantity'] = $pn['product_pool']['available_quantity'];
            $dt['is_final']           = $pn['is_final'];
            $dt['status']             = $pn['status'];
            $dt['placed_at']          = date('d-m-Y', strtotime($pn['created_at']));
            $pool[]                   = $dt;
        }
        return DataTables::of(collect($pool))
            ->addIndexColumn()
            ->editColumn('attribute_pair', function ($pool) {
                return $pool['attribute_pair'];
            })
            ->editColumn('status', function ($pool) {
                if ($pool['status'] == 'requested') {
                    $html = '<span href="#0" class="badge cursor-pointer badge-warning text-center">Negotiating</span>';
                } elseif ($pool['status'] == 'client_confirmed') {
                    $html = '<span href="#0" class="badge cursor-pointer badge-success text-center">Confirmed By Client</span>';
                } elseif ($pool['status'] == 'vendor_confirmed') {
                    $html = '<span href="#0" class="badge cursor-pointer badge-success text-center">Confirmed By Vendor</span>';
                } elseif ($pool['status'] == 'vendor_rejected' ) {
                    $html = '<span href="#0" class="badge cursor-pointer badge-danger text-center">Rejected by vendor</span>';
                } elseif ($pool['status'] == 'client_rejected') {
                    $html = '<span href="#0" class="badge cursor-pointer badge-danger text-center">Rejected by client</span>';
                } else {
                    $html = '<span href="#0" class="badge cursor-pointer badge-success text-center">' . $pool['status'] . '</span>';
                }

                if (!empty($pool['is_final'])) {
                    $html .= '<span href="#0" class="badge cursor-pointer badge-danger text-center">Final Price Placed</span>';
                }
                return $html;

            })
            ->editColumn('original_price', function ($pool) {
                return number_format($pool['original_price'], 2);

            })
            ->editColumn('client_asking_price', function ($pool) {
                return number_format($pool['client_asking_price'], 2);

            })
            ->editColumn('client_asking_total', function ($pool) {
                return number_format($pool['client_asking_total'], 2);

            })
            ->editColumn('vendor_asking_price', function ($pool) {
                return number_format($pool['vendor_asking_price'], 2);

            })
            ->editColumn('vendor_asking_total', function ($pool) {
                return number_format($pool['vendor_asking_total'], 2);

            })
            ->addColumn('action', function ($pool) {
                if ($pool['status'] == 'requested') {
                    if (!empty($pool['is_final'])) {
                        return
                            '<div class="btn-group">
                            <a href="#" data-id="' . $pool['sale_negotiation_id'] . '"
                            class="btn btn-sm btn-success confirm">Confirm</a>
                            <a href="#" data-id="' . $pool['sale_negotiation_id'] . '"
                            class="btn btn-sm btn-danger reject">Reject</a>
                        </div>';
                    } else {
                        return
                            '<div class="btn-group">
                            <a href="#" data-id="' . $pool['sale_negotiation_id'] . '"
                            data-vap="' . $pool['vendor_asking_price'] . '"
                            data-vaq="' . $pool['vendor_asking_quantity'] . '"
                            data-vr="' . $pool['vendor_remarks'] . '"
                            data-cr="' . $pool['client_remarks'] . '"
                            data-caq="' . $pool['client_asking_quantity'] . '"
                            data-final="' . $pool['is_final'] . '"
                            class="btn btn-sm btn-warning negotiate">Negotiate</a>

                            <a href="#" data-id="' . $pool['sale_negotiation_id'] . '"
                            class="btn btn-sm btn-success confirm">Confirm</a>
                            <a href="#" data-id="' . $pool['sale_negotiation_id'] . '"
                            class="btn btn-sm btn-danger reject">Reject</a>
                        </div>';
                    }
                } else {
                    return'';
                }
            })
            ->rawColumns(['attribute_pair', 'status', 'action', 'original_price', 'client_asking_price', 'vendor_asking_price', 'client_asking_total', 'vendor_asking_total'])
            ->make(true);

    }

    public function negotiateOrder(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        DB::beginTransaction();
        try {
            $ngt                         = SaleNegotiation::findOrFail($request->id);
            $ngt->vendor_id    = auth()->user()->vendor_id;
            $ngt->vendor_asking_price    = $request->vendor_asking_price;
            $ngt->vendor_asking_quantity = $request->vendor_asking_quantity;
            $ngt->vendor_asking_total    = $request->vendor_asking_price * $request->vendor_asking_quantity;
            $ngt->vendor_asked_by        = auth()->user()->id;
            $ngt->vendor_remarks         = $request->vendor_remarks;
            $ngt->is_final               = $request->is_final ?? 0;
            $ngt->last_updated_by        = 'vendor';
            $ngt->updated_by             = auth()->user()->id;
            $ngt->updated_at             = now();
            $ngt->save();
        } catch (\Exception$e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'message' => 'Something went wrong', 'raw' => $e->getMessage()]);
        }
        DB::commit();
        return response()->json(['status' => 'success', 'message' => 'Quotation Submitted']);
    }
    public function confirmNegotiation(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json(['status' => 'error', 'message' => 'Method Not Allowed']);
        }
        $negotiation = SaleNegotiation::where('id', $request->id)->with('product')
            ->with('productPool')
            ->with('vendor')
            ->with(['marketplaceUser' => function ($query) {
                $query->with(['marketplaceUserAddreses' => function ($query) {
                    $query->where('is_default_address', 1);
                }]);
            }])->first();
        $marketplace_user_id         = $negotiation->client_asked_by;
        $marketplace_user_address_id = $negotiation->marketplace_user_address_id;
        $delivery_type               = 2;
        $payment_method_id           = 1;
        $shipping_method             = DB::table('shipping_methods')
            ->select('id','charge')
            ->where('id', $delivery_type)
            ->where('status', 1)
            ->first();
        $payment_method             = DB::table('shipping_methods')
            ->select('id')
            ->where('id', $payment_method_id)
            ->where('status', 1)
            ->first();
        $shipping_charge = '0.00';
        if (!empty($shipping_method)) {
            $shipping_charge = $shipping_method->charge;
        }
        $sub_total       = $negotiation->client_asking_total;
        $final_total     = $sub_total + $shipping_charge;
        $tax             = 0;
        $inv_prefix      = DB::table('admin_configs')
            ->select('value')
            ->where('name', 'invoice_prefix')
            ->first();

        //Invoice Prefix
        if (empty($inv_prefix)) {
            $invoice_prefix = 'INV';
        } else {
            $invoice_prefix = $inv_prefix->value;
        }
        DB::beginTransaction();
        try {
            if ($negotiation->productPool->available_quantity < $negotiation->client_asking_quantity) {
                return response()->json(responseFormat('error', 'Product Out Of Stock'));
            }
            if ($negotiation->productPool->available_quantity == $negotiation->client_asking_quantity) {
                $negotiation->productPool->stock_status = 'stock_out';
                $negotiation->last_updated_by           = 'vendor';
                $negotiation->updated_by                = auth()->user()->id;
                $negotiation->productPool->save();
            }

            //Invoice Prefix
            do {
                $in_no                 = strtoupper(uniqid($invoice_prefix) . rand(10, 99));
                $not_unique_invoice_no = Sale::where(['invoice_no' => $in_no])->first();
            } while ($not_unique_invoice_no);
            $vC = auth()->user()->vendor->active_sale_commission;
            $swg = $final_total * ($vC/100);
            $vwg = $final_total - $swg;
            $sale = Sale::create([
                'vendor_id'                   => auth()->user()->vendor_id,
                'type_of_sale'                => 2,
                'marketplace_user_id'         => $marketplace_user_id,
                'invoice_no'                  => $in_no,
                'items'                       => 1,
                'sub_total'                   => $sub_total,
                'tax'                         => $tax . "%",
                'marketplace_user_address_id' => $marketplace_user_address_id,
                'payment_method_id'           => (!empty($payment_method)) ? $payment_method->id : NULL,
                'shipping_method_id'          => (!empty($shipping_method)) ? $shipping_method->id : NULL,
                'shipping_charge'             => $shipping_charge,
                'discount'                    => 0,
                'final_total'                 => $final_total,
                'commission_percentage' => $vC,
                'superadmin_will_get' => $swg,
                'vendor_will_get' => $vwg,
                'status'                      => 'pending',
                'delivery_status'             => 'pending',
                'payment_status'              => 'pending',
                'user_warehouse_id'           => 0,
                'created_by'                  => auth()->user()->id,
                'updated_by'                  => 0,
            ]);

            $saleDetail = SaleDetail::create([
                'sale_id'      => $sale->id,
                'vendor_id'    => $negotiation->vendor_id,
                'product_id'   => $negotiation->product_id,
                'quantity'     => $negotiation->client_asking_quantity,
                'status'       => 'pending',
                'per_price'    => $negotiation->client_asking_price,
                'total'        => $negotiation->client_asking_total,
                'warehouse_id' => 0,
                'created_at'   => now(),
            ]);
            SaleOrder::create([
                'sale_detail_id'      => $saleDetail->id,
                'status'              => 'submitted',
                'submitted_at'        => now(),
                'created_by'          => auth()->user()->id,
                'sale_negotiation_id' => $negotiation->id,
                'created_at'          => now(),
            ]);
            $product_pool = $negotiation->productPool;
            foreach ($product_pool['attribute_map_id'] as $product_attribute_map_id) {
                $product_attribute_map = ProductAttributeMap::select()->with('attributeName')->where('id', $product_attribute_map_id)->first();
                SaleAttributeDetails::create([
                    'sale_detail_id'             => $saleDetail->id,
                    'product_attribute_id'       => $product_attribute_map->product_attribute_id,
                    'product_attribute_name'     => $product_attribute_map->attributeName->name,
                    'product_attribute_map_id'   => $product_attribute_map->id,
                    'product_attribute_map_name' => $product_attribute_map->value,
                ]);
            }
            $product_pool->mp_order_submitted_quantity   = intval($product_pool->mp_order_submitted_quantity) + intval($negotiation->client_asking_quantity);
            $product_pool->mp_order_confirmation_pending = intval($product_pool->mp_order_confirmation_pending) + intval($negotiation->client_asking_quantity);
            $product_pool->save();

            ProductPoolSaleDetail::create([
                'product_pool_id' => $product_pool->id,
                'sale_detail_id'  => $saleDetail->id,
            ]);

            $negotiation->status = 'vendor_confirmed';
            $negotiation->vendor_confirmed_at = now();
            $negotiation->save();

        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(responseFormat('error', $exception->getMessage()));
        }
        DB::commit();
        return response()->json(responseFormat('success', ['invoice_no'=>$in_no, 'message'=>"Order Processing."]));

    }
    public function rejectNegotiation(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $ng = SaleNegotiation::where('id', $request->id)->first();
        if ($ng)
        {
            $ng->update(['status' => 'vendor_rejected', 'vendor_rejected_at' => now(), 'last_updated_by' => 'vendor', 'updated_by' => auth()->user()->id]);
            return response()->json(responseFormat('success',['message'=>"Negotiation rejected"]));
        }else{
            return response()->json(responseFormat('error',"Something went wrong"));

        }
    }

}
