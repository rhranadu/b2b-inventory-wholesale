<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;

use App\Vendor;
use App\VendorExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Carbon\Carbon;
class VendorExpenseReportController extends Controller
{
    public function index()
    {
        $title = 'Vendor Expenses Report';
        $page_detail = 'Vendor Expenses Details Report';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('reports.vendor_expense.index',compact('vendors','title', 'page_detail'));
    }

    public function getReportByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cond = [];
        $where = [['vendor_id', Auth::user()->vendor_id]];
        if(!empty($request->vendor_id) ){
            $cond = [['vendor_id', '=', $request->vendor_id ]];
        }
        if(!empty($request->startDate) && empty($request->endDate) ){
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00']];
        }
        if(!empty($request->endDate) && empty($request->startDate) ){
            $cond = [['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        if(!empty($request->endDate) && !empty($request->startDate) ){
            $cond = [['created_at', '>=', $request->startDate . ' 00:00:00'], ['created_at', '<=', $request->endDate . ' 23:59:59']];
        }
        $where = array_merge($where, $cond);
        $vendor_expenses = VendorExpense::where($where)->latest()->get();

        foreach ($vendor_expenses as $vendor_expense){
            $vendor_expense->pay_amount = number_format($vendor_expense->pay_amount, 2,'.','');
        }

        return DataTables::of($vendor_expenses)
            ->addIndexColumn()
            ->make(true);
    }
}
