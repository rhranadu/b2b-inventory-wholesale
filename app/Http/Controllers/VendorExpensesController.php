<?php

namespace App\Http\Controllers;

use App\VendorExpense;
use App\Http\Requests\VendorExpense\VendorExpenseStore;
use App\Http\Requests\VendorExpense\VendorExpenseUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;
use Illuminate\Support\Str;

class VendorExpensesController extends Controller
{

    public function index()
    {
//        $sale = $this->saleAndAccount();
//        $account_role = in_array('Account',  Auth::user()->roles->pluck('name')->toArray());
//        if (Auth::user()->user_type_id == 2 || ($sale && $account_role) || $account_role)
//        {
//            $vendorexpenses = VendorExpense::where('vendor_id', Auth::user()->vendor_id)->latest()->get();
//        }else if ($sale && !$account_role)
//        {
//            $vendorexpenses = VendorExpense::where(['vendor_id' => Auth::user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id])->latest()->get();
//        }
        $title = 'Create Vendor Expense';
        $page_detail = 'Create a Expense for your Vendor';
        return view('vendor_expenses.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $vendor_expenses = VendorExpense::where('vendor_id', Auth::user()->vendor_id)->latest()->get();

        return Datatables::of($vendor_expenses)
            ->addIndexColumn()
            ->addColumn('action', function ($vendor_expense) {
                return '<div class="btn-group">
                        <a href="/admin/vendorexpenses/' . $vendor_expense->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteVendorExpense('.$vendor_expense->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteVendorExpenseForm-'.$vendor_expense->id.'" action="/admin/vendorexpenses/'. $vendor_expense->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function create()
    {
//        return view('vendor_expenses.create');
    }


    public function store(VendorExpenseStore $request)
    {
//        if ($request->pay_amount == 0)
//        {
//           $status = 'NY';
//        }elseif ($request->total_amount > $request->pay_amount)
//        {
//            $status = 'PP';
//        }elseif ($request->total_amount == $request->pay_amount)
//        {
//            $status = 'FP';
//        }elseif ($request->pay_amount > $request->total_amount)
//        {
//            return redirect()->back()->with('warning', 'Payment is getting longer');
//        }
//
//        $vendorexpenses = VendorExpense::create([
//            'vendor_id' => Auth::user()->vendor_id,
//            'warehouse_id' => Auth::user()->warehouse_id ?? 0,
//            'total_amount' => $request->total_amount,
//            'pay_amount' => $request->pay_amount ?? 0,
//            'reason' => $request->reason,
//            'details' => $request->details,
//            'status' => $status,
//            'created_by' => Auth::id(),
//            'updated_by' => Auth::id(),
//        ]);
        $request['created_by'] = auth()->id();
        $request['vendor_id'] = auth()->user()->vendor_id;
        $vendor_expenses = VendorExpense::create($request->except('_token'));

        if ($vendor_expenses)
        {
            return redirect()->back()->with('success', 'Vendor Expense Created Success');

        }else{
            return redirect()->back()->with('error', 'Vendor Expense Not Created');
        }
    }


    public function show($id)
    {

    }


    public function edit($id)
    {
//        $sale = $this->saleAndAccount();
//        $account_role = in_array('Account',  Auth::user()->roles->pluck('name')->toArray());
//        if (Auth::user()->user_type_id == 2 || ($sale && $account_role) || $account_role)
//        {
//            $vendorexpense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id])->first();
//        }else if ($sale && !$account_role)
//        {
//            $vendorexpense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id])->first();
//        }
        $title = 'Edit Vendor Expense';
        $page_detail = 'Edit a Expense of your Vendor';
        $vendor_expense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id])->first();

        if ($vendor_expense)
        {
            return view('vendor_expenses.edit',  compact('vendor_expense','title', 'page_detail'));
        } else{
            abort(404);
        }
    }


    public function update(VendorExpenseUpdate $request, $id)
    {
//        $sale = $this->saleAndAccount();
//        $account_role = in_array('Account',  Auth::user()->roles->pluck('name')->toArray());
//        if (Auth::user()->user_type_id == 2 || ($sale && $account_role) || $account_role)
//        {
//            $vendorexpense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id])->first();
//        }else if ($sale && !$account_role)
//        {
//            $vendorexpense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id])->first();
//        }
//        if (!$vendorexpense)
//        {
//            abort(404);
//        }
//
//        if ($request->total_amount > $request->pay_amount)
//        {
//            $status = 'PP';
//        }elseif ($request->total_amount == $request->pay_amount)
//        {
//            $status = 'FP';
//        }elseif ($request->pay_amount == 0)
//        {
//            $status = 'NY';
//        }elseif ($request->pay_amount > $request->total_amount)
//        {
//            return redirect()->back()->with('warning', 'Payment is getting longer');
//        }
        $vendor_expense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id])->first();
        if ($vendor_expense){
            $vendor_expense->update([
                'particulars' => $request->particulars,
                'pay_amount' => $request->pay_amount,
                'expense_date' => $request->expense_date,
                'updated_by' => Auth::id(),
            ]);
        }
        return redirect()->action('VendorExpensesController@index')->with('success', 'Vendor Expense Updated Success');
    }


    public function destroy($id)
    {
//        $sale = $this->saleAndAccount();
//        $account_role = in_array('Account',  Auth::user()->roles->pluck('name')->toArray());
//        if (Auth::user()->user_type_id == 2 || ($sale && $account_role) || $account_role)
//        {
//            $vendorexpense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id, 'warehouse_id' => Auth::user()->warehouse_id])->first();
//        }
        $vendor_expense = VendorExpense::where(['id' => $id,'vendor_id' => Auth::user()->vendor_id])->first();

        if ($vendor_expense)
        {
            $vendor_expense->delete();
            return redirect()->back()->with('success', 'Vendor Expense deleted success !');
        }else{
            abort(404);
        }
    }


    private function saleAndAccount()
    {
        $sale = false;
        $sale_role = in_array('Sale', Auth::user()->roles->pluck('name')->toArray());
        if(Auth::user()->warehouse_type_name === 'show-room' && $sale_role)
        {
            $sale = true;
        }else{
            $sale = false;
        }
        return $sale;
    }

}
