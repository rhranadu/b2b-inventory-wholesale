<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\ProductStock;
use App\Purchase;
use App\PurchaseDetail;
use App\Sale;
use App\SupplierPaymentTransaction;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
class SupplierLedgersReportController extends Controller
{

    public function index()
    {
        $title = 'Supplier Ledgers Report';
        $page_detail = 'Supplier Ledgers Details Report';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.suppliers.supplier_ledgers',compact('vendors','title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $supplier_ledgers = SupplierPaymentTransaction::with('purchaseSupplier');
        if(!empty(Auth::user()->vendor_id) ){
            $supplier_ledgers->where('vendor_id', Auth::user()->vendor_id);
        }
        if(!empty($request->vendor_id) ){
            $supplier_ledgers->where('vendor_id', $request->vendor_id);
        }
        if(!empty($request->supplier_id) ){
            $supplier_ledgers->where('supplier_id', $request->supplier_id );
        }
        if(!empty($request->startDate) && !empty($request->endDate) ){
            $start_date = date('Y-m-d',strtotime($request->startDate));
            $end_date = date('Y-m-d',strtotime($request->endDate));
            $supplier_ledgers->where('created_at', '>=', $start_date . ' 00:00:00');
            $supplier_ledgers->where('created_at', '<=', $end_date . ' 23:59:59');
        }
        $supplier_ledgers = $supplier_ledgers->latest()->get();

        foreach ($supplier_ledgers as $supplier_ledger){
            if ($supplier_ledger->transaction_date){
                $supplier_ledger->transaction_date = $supplier_ledger->transaction_date;
            }else{
                $supplier_ledger->transaction_date = 'N/A';
            }
        }


        return Datatables::of($supplier_ledgers)
            ->addIndexColumn()
            ->make(true);
    }


}
