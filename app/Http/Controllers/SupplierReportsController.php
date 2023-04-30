<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Supplier;
use Illuminate\Http\Request;

class SupplierReportsController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::with('bankAccount','supplierPurchases', 'supplierPurchasesDetails', 'vendorPaymentTranstion')->where('vendor_id', auth()->user()->vendor_id)->get();
        return view('reports.supplier.index', compact('suppliers'));
    }
    public function supplierReportIndetails($id)
    {
        $supplier = Supplier::where(['id' =>$id])->first();
        if (auth()->user()->user_role->name === 'admin' || auth()->user()->vendor_id == $supplier->vendor_id){
            return view('reports.supplier.in_details', compact('supplier'));
        }else{
            return redirect()->back()->with('warning', 'Wrong Url');
        }
    }


}
