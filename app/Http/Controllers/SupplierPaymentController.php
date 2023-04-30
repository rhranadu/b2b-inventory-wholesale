<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddPaymentMethodRequest;
use App\Supplier;
use App\SupplierPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendor_suppliers =  Supplier::where([
            'vendor_id' => Auth::user()->vendor_id,
            'type' => 'supplier',
            'status' => 1,
        ])->get();

        return view('supplier_payment.index', compact('vendor_suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Supplier $supplier)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Supplier $supplier)
    {
//        dd($request);

        if ($supplier->vendor_id != Auth::user()->vendor_id)
        {
            abort(404);
        }
        if ($request->visible_name){
            $visible_name = $request->visible_name;
        }if ($request->bank_transfer){
        $visible_name = $request->bank_name . '|' . $request->account_no ;
        }if ($request->mobile_banking){
            $visible_name = $request->mobile_service_name . '|' . $request->account_no ;
        }

        $request['status'] = $request->status ?? 0;

        SupplierPaymentMethod::create([
            'supplier_id' => $supplier->id,
            'vendor_id' => $supplier->vendor_id,
            'payment_type' => $request->payment_type,
            'account_no' => $request->account_no,
            'visible_name' => $visible_name,
            'bank_name' => $request->bank_name,
            'branch_name' => $request->branch_name,
            'bank_account_name' => $request->bank_account_name,
            'swift_code' => $request->swift_code,
            'mobile_service_name' => $request->mobile_service_name,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Payment method successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SupplierPaymentMethod $supplierPaymentMethod)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function activeUnactivePaymentMethod(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $supplierPaymentMethod = SupplierPaymentMethod::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
        if ($supplierPaymentMethod)
        {
            $supplierPaymentMethod->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }
}
