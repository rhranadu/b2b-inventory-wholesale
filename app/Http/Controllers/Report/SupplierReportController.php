<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierReportController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('bankAccount','supplierPurchases', 'payments')->where('vendor_id', Auth::user()->vendor_id)->latest()->paginate(10);
        return view('reports.supplier.index', compact('suppliers'));
    }

    public function supplierDetail(Supplier $supplier)
    {
        if (Auth::user()->vendor_id === $supplier->vendor_id)
        {
            return view('reports.supplier.in_details', compact('supplier'));
        }else{
            abort(403);
        }
    }


}
