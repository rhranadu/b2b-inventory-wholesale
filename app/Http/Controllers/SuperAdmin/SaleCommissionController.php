<?php

namespace App\Http\Controllers\SuperAdmin;

use App\AdminConfig;
use App\Http\Controllers\Controller;
use App\SaleCommission;
use App\Tax;
use App\Vendor;
use App\VendorCommissionTransaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SaleCommissionController extends Controller
{

    public function index(Request $request)
    {
        
        if (!$request->ajax()) {
            $title       = 'Sale Commissions';
            $page_detail = 'List of sale commissions';
            $vendors     = Vendor::where('status', 1)->pluck('name', 'id');
            $global      = AdminConfig::where('name', 'global_sale_commission_percentage')->first();
            return view('super_admin.sale_commissions.index', compact('title', 'page_detail', 'global', 'vendors'));
        }

        $data = SaleCommission::with('commissionVendor');
        if(!empty($request->vendor_id)){
            $data->where('vendor_id', $request->vendor_id);
        }
        $data = $data->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('vendor', function ($data) {
                return $data->commissionVendor->name;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 1) {
                    return '<span href="#0" id="activeInactive" statusCode="' . $data->status . '" data_id="' . $data->id . '"   class="badge cursor-pointer badge-success">Active</span>';
                }

                return '<span href="#0" id="activeInactive" statusCode="' . $data->status . '" data_id="' . $data->id . '"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($data) {
                return '<div class="btn-group">
                        <a href="#" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT" onclick="renderVendorStore(' . $data->id . ')"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="destroyData(' . $data->id . ')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="destroyDataForm-' . $data->id . '" action="/superadmin/sale-commission/' . $data->id . '">
                        ' . csrf_field() . '
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function globalSaleCommission(Request $request)
    {

        if (!$request->ajax()) {
            $title       = 'Global Sale Commission';
            $page_detail = 'Global Sale Commissions';
            $global      = AdminConfig::where('name', 'global_sale_commission_percentage')->first();
            return view('super_admin.sale_commissions.global', compact('title', 'page_detail', 'global'));
        }

        try {
            $config             = AdminConfig::find($request->id);
            $config->value      = $request->commission;
            $config->updated_by = Auth::user()->id;
            $config->save();
        } catch (\Exception $e) {
            return response()->json('false');
        }
        return response()->json('true');
    }
    public function renderVendorSaleCommission(Request $request)
    {
        $vendors    = Vendor::where('status', 1)->pluck('name', 'id');
        $commission = SaleCommission::find($request->id);
        return view('super_admin.sale_commissions.vendor_sale_commission_create', compact('vendors', 'commission'));
    }

    public function vendorSaleCommissionStore(Request $request)
    {
        $existingComm = SaleCommission::where('vendor_id', $request->vendor_id)->where('status', 1)->get();
        try {
            if(!empty($existingComm)){
                if($request->status || intval($request->status) > 0){
                    foreach ($existingComm as $comm) {
                        $comm->status = 0;
                        $comm->updated_by = Auth::user()->id;
                        $comm->save();
                    }
                }
            }
            if (!empty($request->id)) {
                $comm             = SaleCommission::find($request->id);
                $comm->updated_by = Auth::user()->id;
            } else {
                $comm = new SaleCommission();
            }
            $comm->vendor_id             = $request->vendor_id;
            $comm->commission_percentage = $request->commission_percentage;
            $comm->status                = $request->status ?? 0;
            $comm->created_by            = Auth::user()->id;
            $comm->save();
        } catch (\Exception $e) {
            return response()->json(['msg'=>'Somethis went wrong', 'success' => 'false']);
        }
        return response()->json(['msg'=>'Successfully addedd', 'success' => 'true']);
    }

    public function destroy(SaleCommission $saleCommission)
    {
        if (getActiveGuard() != 'superadmin') {
            return redirect()->back()->with('Not allow to delete');
        } 
        $saleCommission->delete();
        return redirect()->back()->with('success', 'Deleted !');
    }

    // public function activeUnactive(Request $request)
    // {
    //     if (!$request->ajax()) {
    //         abort(404);
    //     }

    //     $tax = Tax::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
    //     if ($tax) {
    //         $tax->update(['status' => $request->setStatus]);
    //         return response()->json('true');
    //     } else {
    //         return response()->json('false');
    //     }
    // }

    public function receivedCommissions(Request $request)
    {
        if (!$request->ajax()) {
            $title       = 'Sale Commissions';
            $page_detail = 'Sale Commissions';
            $vendors     = Vendor::where('status', 1)->pluck('name', 'id');
            return view('super_admin.sale_commissions.received_commissions', compact('title', 'page_detail', 'vendors'));
        }

        $data     = VendorCommissionTransaction::with('vendor');
        if(!empty($request->vendor_id)){
            $data->where('vendor_id', $request->vendor_id);
        }
        $data = $data->get();

        return Datatables::of($data)
            ->addIndexColumn()
            ->editColumn('vendor', function ($data) {
                return $data->vendor->name;
            })
            ->editColumn('status', function ($data) {
                if ($data->status == 'Submitted') {
                    return '<span href="#0" class="badge cursor-pointer badge-danger">Submitted</span>';
                } 

                return '<span href="#0" class="badge cursor-pointer badge-success">Received</span>';
            })
            ->addColumn('action', function ($data) {
                if ($data->status == 'Submitted') {
                    return '<div class="btn-group">
                    <a href="#" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="Receive Payment" data-original-title="EDIT" onclick="receivePayment(' . $data->id . ')"><i class="fas fa-check"></i></a>
                    </div>';
                } 
                return '<span href="#0" class="badge cursor-pointer badge-success">Received</span>';
            })
            ->rawColumns(['vendor', 'status', 'action'])
            ->make(true);
    }


    public function receivePayment(VendorCommissionTransaction $vct)
    {
        $vct->status = 'Received';
        $vct->save();
        return response()->json('true');
    }

}
