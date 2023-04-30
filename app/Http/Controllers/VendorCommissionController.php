<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\Http\Controllers\Controller;
use App\SaleCommission;
use App\Tax;
use App\Sale;
use App\VendorCommissionTransaction;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class VendorCommissionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            $vendorCommission = auth()->user()->vendor->active_sale_commission;
            $title = 'Commission Payment';
            $page_detail = 'Commission Payment';
            $sale = Sale::where('vendor_id', auth()->user()->vendor_id)->get();
            $trans = VendorCommissionTransaction::where('vendor_id', auth()->user()->vendor_id)->get();
            $totalSale = 0;
            $totalCommission = 0;
            $totalPaid = 0;
            $totalReceived = 0;
            foreach($trans as $t){
                if($t->status == 'Submitted'){
                    continue;
                }
                $totalReceived = $totalReceived + $t->amount;  
            }
            foreach($trans as $t){
                $totalPaid = $totalPaid + $t->amount;  
            }
            foreach($sale as $s){
                $totalSale = $totalSale + $s->final_total;  
                $totalCommission = $totalCommission + $s->superadmin_will_get;  
            }
            $totalCommission = $totalCommission - $totalReceived;
            return view('vendor_commission.index', compact('title', 'page_detail', 'vendorCommission','totalSale','totalCommission','totalPaid','totalReceived'));
        }
    }
    public function transactionCreate(Request $request)
    {
        if (!$request->ajax()) {
            $title = 'Commission Payment';
            $page_detail = 'Commission Payment';
            return view('vendor_commission.transaction_create', compact('title', 'page_detail'));
        }
    }
    public function transactionStore(Request $request)
    {
        $tra = new VendorCommissionTransaction();
        $tra->vendor_id = auth()->user()->vendor_id;
        $tra->amount = $request->amount;
        $tra->note = $request->note;
        $tra->payment_date = $request->payment_date;
        $tra->status = 'Submitted';
        $tra->created_by = auth()->user()->id;
        $tra->updated_by = auth()->user()->id;
        $tra->save();
        return redirect()->action('VendorCommissionController@index')->with('success', 'Payment Success !');
    }



}