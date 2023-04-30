<?php

namespace App\Http\Controllers\SuperAdmin\Report;

use App\Http\Controllers\Controller;
use App\Sale;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class SaleReportController extends Controller
{
    public function saleComissionDetail(Request $request)
    {
        if (!$request->ajax()) {
            $title       = 'Sale Commission Report';
            $page_detail = 'Sale Commission Report';
            $vendors     = Vendor::select('id', 'name')
                ->where([
                    ['status', 1],
                ])
                ->get();
            return view('super_admin.reports.sales.commission_detail', compact('title', 'page_detail', 'vendors'));
        }
        $query = Sale::with('saleWarehouse', 'posCustomer', 'payment', 'vendor');
        if (!empty($request->vendor_id)) {
            $query->where('vendor_id', $request->vendor_id);
        } elseif (!empty(auth()->user()->vendor_id)) {
            $query->where('vendor_id', auth()->user()->vendor_id);
        }

        if (!empty(request()->start_date) && empty(request()->end_date)) {
            $query->where('created_at', '>=', request()->start_date . ' 00:00:00');
        }
        if (!empty(request()->end_date) && empty(request()->start_date)) {
            $query->where('created_at', '<=', request()->end_date . ' 23:59:59');
        }
        if (!empty(request()->end_date) && !empty(request()->start_date)) {
            $query->where('created_at', '>=', request()->start_date . ' 00:00:00');
            $query->where('created_at', '<=', request()->end_date . ' 23:59:59');
        }
        if (!empty(request()->invoice_no)) {
            $query->where('invoice_no', trim(request()->invoice_no));
        }
        $query = $query->latest()->get();
        return DataTables::of($query)
            ->editColumn('vendor_name', function ($query) {
                return $query->vendor->name;
            })
            ->rawColumns(['vendor_name'])
            ->addIndexColumn()
            ->make(true);
    }

    public function salesInvoices(Request $request)
    {
        if ($request->search) {
            $sale_invoices = Sale::where('invoice_no', 'like', "%{$request->search}%")->get();
        } else {
            $sale_invoices = Sale::get();
        }

        $invoices = array();
        foreach ($sale_invoices as $value) {
            $invoices[$value->invoice_no] = $value->invoice_no;
        }
        return $invoices;
    }

}
