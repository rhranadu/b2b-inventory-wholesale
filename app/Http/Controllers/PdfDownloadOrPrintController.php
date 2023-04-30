<?php

namespace App\Http\Controllers;

use App\Purchase;
use App\Sale;
use App\Supplier;
use App\SupplierPaymentTransaction;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PdfDownloadOrPrintController extends Controller
{

    public function purchasesPdf($id)
    {
        $purchase = Purchase::findOrFail($id);
        if (auth()->user()->vendor_id == $purchase->vendor_id)
        {
            $pdf = PDF::loadView('pdf.purchase', ['purchase' => $purchase]);
            return $pdf->stream();
        }else{
            abort(404);
        }
    }


    public function purchasesStorePricePdf($id)
    {
        $purchase = Purchase::findOrFail($id);
        if (auth()->user()->vendor_id == $purchase->vendor_id)
        {
            $pdf = PDF::loadView('pdf.purchase_store_details', ['purchase' => $purchase]);
            return $pdf->stream();
        }else{
            abort(404);
        }
    }


    public function salePrint($id)
    {
        if (Auth::user()->user_type_id == 0)
        {
            $sale = Sale::where([ 'id' => $id, 'vendor_id' => Auth::user()->vendor_id, 'user_warehouse_id' => Auth::user()->warehouse_id])->first();
        }else{
            $sale = Sale::where('id', $id)->first();
        }

        if (Auth::user()->vendor_id == $sale->vendor_id)
        {
            $pdf = PDF::loadView('pdf.make_sale_pdf', ['sale_pdf' => $sale]);
            return $pdf->stream();
        }else{
            abort(404);
        }
    }


    public function supplierLedgerPrint($id)
    {
        $supplier = Supplier::findOrFail($id);
        if ($supplier->vendor_id == Auth::user()->vendor_id) {
            $supplier_payments_transactions = SupplierPaymentTransaction::where(['vendor_id' => Auth::user()->vendor_id, 'supplier_id' => $supplier->id])
                ->get();
            $pdf = PDF::loadView('pdf.vendor_ledger', ['supplier_payments_transactions' => $supplier_payments_transactions, 'vendor' => $supplier]);
            return $pdf->stream();
        } else {
            abort(404);
        }
    }


}
