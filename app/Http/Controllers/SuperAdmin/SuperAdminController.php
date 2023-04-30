<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Sale;
use App\Vendor;
use App\VendorCommissionTransaction;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SuperAdminController extends Controller
{

    public function index()
    {
        $title = 'Super Admin Dashboard';
        $page_detail = 'Super Admin Dashboard Details';
        $total_sa_commission = Sale::where( function ($q) {
                        $q->orWhere('status','delivered');
                        $q->orWhere('status','confirmed');
                        $q->orWhere('status','processed');
                        $q->orWhere('status','shipped');
                    })->get()->sum('superadmin_will_get');

        $total_vendor = Vendor::where('status',1)->get()->count();

        return view('super_admin.dashboard.dashboard',compact('total_vendor','total_sa_commission','title', 'page_detail'));
    }

    public function vendorWiseCommission(Request $request){
        if (!$request->ajax())
        {
            abort(404);
        }
        $vendor_commission = Sale::with('vendor')->where( function ($q) {
                                        $q->orWhere('status','delivered');
                                        $q->orWhere('status','confirmed');
                                        $q->orWhere('status','processed');
                                        $q->orWhere('status','shipped');
                                    })->get();

        $v_s_c = array();
        foreach($vendor_commission as $key => $v_c){
            $v_s_c[$v_c->vendor_id]['vendor_name'] = $v_c->vendor->name;
            $v_s_c[$v_c->vendor_id]['vendor_id'] = $v_c->vendor_id;

            $vendor_pay = VendorCommissionTransaction::where('vendor_id', $v_c->vendor_id)->where('status', '!=', 'Submitted')->sum('amount');
            $v_s_c[$v_c->vendor_id]['vendor_pay'] = $vendor_pay;

            if (isset($v_s_c[$v_c->vendor_id]['superadmin_will_get'])){
                $v_s_c[$v_c->vendor_id]['superadmin_will_get'] += $v_c->superadmin_will_get;
            }else{
                $v_s_c[$v_c->vendor_id]['superadmin_will_get'] = $v_c->superadmin_will_get;
            }
        }
        return DataTables::of($v_s_c)
            ->addIndexColumn()

            ->editColumn('vendor_name', function ($v_c) {
                return $v_c['vendor_name'];
            })

            ->editColumn('vendor_pay', function ($v_c) {
                return '৳ '.$v_c['vendor_pay'];
            })

            ->editColumn('superadmin_will_get', function ($v_c) {
                return '৳ '.($v_c['superadmin_will_get'] == null ? 0 : $v_c['superadmin_will_get']);
            })

            ->editColumn('vendor_due', function ($v_c) {
                return '৳ '.($v_c['superadmin_will_get'] - $v_c['vendor_pay']);
            })
            ->rawColumns(['vendor_pay','superadmin_will_get','vendor_due','vendor_name'])
            ->make(true);

    }

}
