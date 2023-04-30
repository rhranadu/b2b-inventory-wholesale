<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosCustomer\PosCustomerPaymentStore;
use App\ProductBrand;
use App\ProductCategory;
use App\Sale;
use App\SalePayment;
use App\Vendor;
use App\PosCustomer;
use App\Http\Requests\PosCustomer\PosCustomerStore;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class PosCustomerController extends Controller
{

    public function index()
    {
        if(auth()->user()->user_type_id == 1)
        {
            $poscustomers = PosCustomer::latest()->get();
        }else{
            $poscustomers = PosCustomer::where('vendor_id', auth()->user()->vendor_id)->latest()->get();
        }
        return view('pos_customers.index', compact('poscustomers'));
    }

    public function allList()
    {
        $title = 'Pos Customer';
        $page_detail = 'List of Pos Customer';
        return view('pos_customers.all_list', compact('title', 'page_detail'));
    }

    public function getAllListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $pos_customers = PosCustomer::where('vendor_id', auth()->user()->vendor_id)->latest()->get();
        foreach ($pos_customers as &$pos_customer){
            $pos_customer->details = Str::limit($pos_customer->details,50);
            $pos_customer->total_due = 0;
            $sales = Sale::where('pos_customer_id',$pos_customer->id)->get();
            if (!empty($sales)) {
                foreach ($sales as $sale) {
                    $sale_payment = SalePayment::where('sale_id', $sale->id)->orderBy('id', 'desc')->first();
                    if (!empty($sale_payment)) {
                        $pos_customer->total_due += $sale_payment->due_amount;
                    }
                }
            }
        }
        return DataTables::of($pos_customers)
            ->addIndexColumn()

//            ->editColumn('status', function ($pos_customer) {
//                if ($pos_customer->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$pos_customer->status.'" data_id="'.$pos_customer->id.'"   class="badge cursor-pointer badge-success">Active</span>';
//                return '<span href="#0" id="ActiveUnactive" statusCode="'.$pos_customer->status.'" data_id="'.$pos_customer->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
//            })
//            ->editColumn('image', function ($supplier) {
//                if ($supplier->image)  return '
//                        <div class="pop_img" data-img="'.asset($supplier->image).'">
//                            <img width="50"
//                                 src="'.asset($supplier->image).'"
//                                 alt="image">
//                        </div>
//                ';
//                return '
//                        <div>
//                            No Image
//                        </div>
//                ';
//            })
            ->addColumn('action', function ($pos_customer) {
                return '<div class="btn-group">
                        <a href="/admin/report/sale-wise-due/?pos_customer_id=' . $pos_customer->id . '"   class="btn btn-sm btn-icon btn-info" data-toggle="tooltip" data-placement="auto" title="Payment History" data-original-title="Payment History" target="_blank"><i class="fas fa-history"></i></a>
                        <a href="/admin/add/pos_customer_payment/' . $pos_customer->id . '" style="background: #00c292"  class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="auto" title="Submit a Payment" data-original-title="Submit a Payment"><i class="lab la-stripe-s"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deletePosCustomer('.$pos_customer->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="supplierDeleteForm-'.$pos_customer->id.'" action="/admin/poscustomer/'. $pos_customer->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
//            <a href="/admin/poscustomer/' . $pos_customer->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>

            ->rawColumns(['status','image','action'])
            ->make(true);
    }

    public function addPosCustomerPayment(PosCustomer $pos_customer)
    {
        $sale_id = request()->has('sale_id') ? request()->query('sale_id') : 0;
        if(isset($pos_customer->name)){
            $title = $pos_customer->name.' | Submit Payment ';
            $page_detail = 'Submit a Payment for ' .$pos_customer->name ;
        }
        if ($pos_customer->vendor_id != Auth::user()->vendor_id)
        {
            abort(404);
        }
        $sale_data = array();
        if (!empty($sale_id)) {
            $sale = Sale::where('id', $sale_id)->first();
            $sale_payment = SalePayment::where('sale_id',$sale->id)->where('pos_customer_id',$sale->pos_customer_id)->where('warehouse_id','!=',0)->latest()->first();
            if (!empty($sale_payment)) {
                $sale_data['invoice_no'] = $sale->invoice_no;
                $sale_data['id'] = $sale->id;
                $sale_data['due_payment'] = $sale_payment->due_amount;
            }
        }
        return view('pos_customers.pos_customer_payment_submit', compact('pos_customer','title', 'page_detail','sale_id','sale_data'));
    }
    public function getPosCustomerInvoiceList(Request $request) {
        if($request->ajax()){
            $sale_id = request()->has('sale_id') ? request()->query('sale_id') : 0;
            $datas = Sale::query();
            if(!empty($sale_id)){
                $datas->where('id', $sale_id);
            }
            if(!empty(auth()->user()->vendor_id)){
                $datas->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty($request->pos_customer)){
                $datas->where('pos_customer_id', $request->pos_customer);
            }
            if(!empty($request->pos_customer_id)){
                $datas->where('pos_customer_id', $request->pos_customer_id);
            }
            $invoices = $datas->get();
            $data = [];
            foreach ($invoices as $value){
                $due_data = SalePayment::where('sale_id',$value->id)->where('pos_customer_id',$value->pos_customer_id)->where('warehouse_id','!=',0)->latest()->first();
                if (!empty($due_data) && (!empty(intval($due_data->due_amount)))) {
                    $data[$value->id] = $value->invoice_no . ' [Due: ' . $due_data->due_amount . ']';
                }
            }
            return response()->json($data, Response::HTTP_OK);
        }
    }

    public function addPosCustomerPaymentStore(PosCustomerPaymentStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->amount <= 0) {
                return redirect()->back()->with('error', 'Please insert valid amount!');
            }
            $vendor_id =  SalePayment::where([
                'pos_customer_id' => $request->pos_customer_id,
            ])->value('vendor_id');
            if ($vendor_id != Auth::user()->vendor_id)
            {
                abort(404);
            }
            if ($request->sale_id) {
                $sale = Sale::find($request->sale_id);
                if (empty($sale)){
                    return redirect()->back()->with('error', "Invalid Invoice");
                }
                $getSalesPaymentRow = SalePayment::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'pos_customer_id' => $request->pos_customer_id,
                    'sale_id' => $request->sale_id,
                ])->orderBy('id','desc')->first();
                if (empty($getSalesPaymentRow)){
                    return redirect()->back()->with('error', "Invalid Invoice");
                }
                if ($getSalesPaymentRow->due_amount < $request->amount){
                    return redirect()->back()->with('error', "Can't Pay More than Invoice Due");
                }

                if (isset($getSalesPaymentRow->due_amount)) {
                    $total_balance = ($getSalesPaymentRow->due_amount - $request->amount);
                    $total_balance = number_format($total_balance, 2, '.', '');
                } else {
                    $total_balance = 0;
                }

                if ($total_balance > 0) {
                    $status = 'PP';
                } else {
                    $status = 'FP';
                }
                $sale_payment = SalePayment::create([
                    'vendor_id' => $vendor_id,
                    'pos_customer_id' => $request->pos_customer_id,
                    'sale_id' => $sale->id,
                    'warehouse_id'=> $getSalesPaymentRow->warehouse_id,
                    'final_total'=>$getSalesPaymentRow->final_total,
                    'pay_amount' => $request->amount,
                    'due_amount' => $total_balance,
                    'payment_by' => $request->paymentMethod,
                    'check_no' => $request->cheque_no,
                    'status'=> $status,
                    'give_back'=> 0,
                    'comment' => $request->comment,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'bank_account_name' => $request->bank_account_name,
                    'account_no' => $request->account_no,
                    'transaction_no' => $request->transaction_no,
                    'swift_code' => $request->swift_code,
                    'mobile_service_name' => $request->mobile_service_name,
                    'cheque_date' => $request->cheque_date,
                    'transaction_date' => $request->transaction_date,
                    'card_name' => $request->card_name,
                    'card_number' => $request->card_number,
                    'payment_date' => $request->payment_date,
                    'created_by' => Auth::id(),
                ]);
                $image = $request->file('image');
                if ($image)
                {
                    $path_info = getPathInfo(['pos_customers',$sale_payment->pos_customer_id,'sale_payments',$sale_payment->id]);
                    makeDirectory($path_info,0755);
                    $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                    $imageLocation = 'backend/uploads/pos_customers/'.$sale_payment->pos_customer_id.'/sale_payments/'.$sale_payment->id.'/'.$setName;
                    Image::make($image)->save($path_info.'/'.$setName, 100);
                    $sale_payment->image = $imageLocation;
                    $sale_payment->save();
                }
                $sale_update = Sale::where('id',$sale->id)->update(['status'=> $status]);

            }else if (!$request->sale_id){
                $sale = Sale::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'pos_customer_id' => $request->pos_customer_id,
                ])->orderBy('id', 'asc')->get();
                $total_payable = 0;
                foreach ($sale as $key => $value){
                    $getSalesPaymentRow = SalePayment::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'pos_customer_id' => $request->pos_customer_id,
                        'sale_id' => $value->id,
                    ])->orderBy('id', 'desc')->first();
                    $total_payable += $getSalesPaymentRow->due_amount;
                }
                if ($request->amount > $total_payable){
                    return redirect()->back()->with('error', "Can't Pay More than Total Due");
                }
                $Amount_left = $request->amount;
                foreach ($sale as $key => $value){
                    $getSalesPaymentRow = SalePayment::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'pos_customer_id' => $request->pos_customer_id,
                        'sale_id' => $value->id,
                    ])->orderBy('id', 'desc')->first();

                    if ( intval($Amount_left) != 0 && $getSalesPaymentRow->due_amount < $Amount_left && !empty(intval($getSalesPaymentRow->due_amount))) {
                        $sale_payment = SalePayment::create([
                            'vendor_id' => $vendor_id,
                            'pos_customer_id' => $request->pos_customer_id,
                            'sale_id' => $value->id,
                            'warehouse_id'=> $getSalesPaymentRow->warehouse_id,
                            'final_total'=>$getSalesPaymentRow->final_total,
                            'pay_amount' => $getSalesPaymentRow->due_amount,
                            'due_amount' => 0.00,
                            'payment_by' => $request->paymentMethod,
                            'check_no' => $request->cheque_no,
                            'status'=> 'FP',
                            'give_back'=> 0,
                            'comment' => $request->comment,
                            'bank_name' => $request->bank_name,
                            'branch_name' => $request->branch_name,
                            'bank_account_name' => $request->bank_account_name,
                            'account_no' => $request->account_no,
                            'transaction_no' => $request->transaction_no,
                            'swift_code' => $request->swift_code,
                            'mobile_service_name' => $request->mobile_service_name,
                            'cheque_date' => $request->cheque_date,
                            'transaction_date' => $request->transaction_date,
                            'card_name' => $request->card_name,
                            'card_number' => $request->card_number,
                            'payment_date' => $request->payment_date,
                            'created_by' => Auth::id(),
                        ]);
                        $image = $request->file('image');
                        if ($image)
                        {
                            $path_info = getPathInfo(['pos_customers',$sale_payment->pos_customer_id,'sale_payments',$sale_payment->id]);
                            makeDirectory($path_info,0755);
                            $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                            $imageLocation = 'backend/uploads/pos_customers/'.$sale_payment->pos_customer_id.'/sale_payments/'.$sale_payment->id.'/'.$setName;
                            Image::make($image)->save($path_info.'/'.$setName, 100);
                            $sale_payment->image = $imageLocation;
                            $sale_payment->save();
                        }
                        $sale_update = Sale::where('id',$value->id)->update(['status'=>'FP']);
                        $Amount_left = $Amount_left - $getSalesPaymentRow->due_amount;
                    }elseif( intval($Amount_left) != 0 && $getSalesPaymentRow->due_amount > $Amount_left) {
                        $sale_payment = SalePayment::create([
                            'vendor_id' => $vendor_id,
                            'pos_customer_id' => $request->pos_customer_id,
                            'sale_id' => $value->id,
                            'warehouse_id'=> $getSalesPaymentRow->warehouse_id,
                            'final_total'=>$getSalesPaymentRow->final_total,
                            'pay_amount' => number_format(($Amount_left), 2, '.', ''),
                            'due_amount' => number_format(($getSalesPaymentRow->due_amount - $Amount_left), 2, '.', ''),
                            'payment_by' => $request->paymentMethod,
                            'check_no' => $request->cheque_no,
                            'status'=> 'PP',
                            'give_back'=> 0,
                            'comment' => $request->comment,
                            'bank_name' => $request->bank_name,
                            'branch_name' => $request->branch_name,
                            'bank_account_name' => $request->bank_account_name,
                            'account_no' => $request->account_no,
                            'transaction_no' => $request->transaction_no,
                            'swift_code' => $request->swift_code,
                            'mobile_service_name' => $request->mobile_service_name,
                            'cheque_date' => $request->cheque_date,
                            'transaction_date' => $request->transaction_date,
                            'card_name' => $request->card_name,
                            'card_number' => $request->card_number,
                            'payment_date' => $request->payment_date,
                            'created_by' => Auth::id(),
                        ]);
                        $image = $request->file('image');
                        if ($image)
                        {
                            $path_info = getPathInfo(['pos_customers',$sale_payment->pos_customer_id,'sale_payments',$sale_payment->id]);
                            makeDirectory($path_info,0755);
                            $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                            $imageLocation = 'backend/uploads/pos_customers/'.$sale_payment->pos_customer_id.'/sale_payments/'.$sale_payment->id.'/'.$setName;
                            Image::make($image)->save($path_info.'/'.$setName, 100);
                            $sale_payment->image = $imageLocation;
                            $sale_payment->save();
                        }
                        $Amount_left = 0.00;
                    }
                }
            }
//dd('dd');
            // Commit Transaction
            DB::commit();

            return redirect()->back()->with('success', 'Successfully Payment done!');

        } catch (\Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    public function posCustomerListJson(Request $request)
    {
        if($request->ajax()){
            if(auth()->user()->user_type_id == 1) {
                $poscustomers = PosCustomer::where('status', 1);
            }else{
                $poscustomers = PosCustomer::where('status', 1);
                $poscustomers->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty($request->search)){
                $poscustomers->where('name', 'like', '%'.trim($request->search).'%');
            }
            $poscustomers = $poscustomers->paginate(10);
            return response()->json($poscustomers, Response::HTTP_OK);
        }
    }

    public function productCategoryListJson(Request $request)
    {
        if($request->ajax()){
            if(auth()->user()->user_type_id == 1) {
                $productCategory = ProductCategory::where('status', 1);
            }else{
                $productCategory = ProductCategory::where('status', 1);
//                $productCategory->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty($request->search)){
                $productCategory->where('name', 'like', '%'.trim($request->search).'%');
            }
            $productCategory = $productCategory->paginate(10);
            return response()->json($productCategory, Response::HTTP_OK);
        }
    }

    public function productBrandListJson(Request $request)
    {
        if($request->ajax()){
            if(auth()->user()->user_type_id == 1) {
                $productBrand = ProductBrand::where('status', 1);
            }else{
                $productBrand = ProductBrand::where('status', 1);
                $productBrand->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty($request->search)){
                $productBrand->where('name', 'like', '%'.trim($request->search).'%');
            }
            $productBrand = $productBrand->paginate(10);
            return response()->json($productBrand, Response::HTTP_OK);
        }
    }
    public function create()
    {
        $vendors = Vendor::all();
       return view('pos_customers.create', compact('vendors'));
    }


    public function store(PosCustomerStore $request)
    {
        $request['status'] = $request->status ?? 0;
        $request['vendor_id'] = $request->vendor_id ?? auth()->user()->vendor_id;
        $newCustomer = PosCustomer::create([
            'vendor_id' => $request->vendor_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        if($request->ajax()){
            return response()->json($newCustomer->id, Response::HTTP_OK);
        }
        return redirect()->action('PosCustomerController@index')->with('success', 'PosCustomer Created Success !');
    }


    public function posCustomerCreateWithAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()->all()]);
        }else{
            $request['status'] = $request->status ?? 0;
            $request['vendor_id'] = $request->vendor_id ?? auth()->user()->vendor_id;
            PosCustomer::create([
                'vendor_id' => $request->vendor_id,
                'warehouse_id' => Auth::user()->warehouse_id ?? 0,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);
            return response()->json('success');
        }

    }


    public function addBankForm(PosCustomer $poscustomer)
    {
        // only allow auth user and admin
        if($poscustomer->vendor_id == auth()->user()->vendor_id || auth()->user()->user_role->name == 'admin')
        {
            return view('pos_customers.bank', compact('poscustomer'));
        }else{
            return redirect()->back()->with('warning', 'Wrong url');
        }

    }

    public function addBankStore(Request $request, PosCustomer $poscustomer)
    {
    }


    public function show(PosCustomer $poscustomer)
    {
        //
    }


    public function edit(PosCustomer $poscustomer)
    {
        //
    }


    public function update(Request $request, PosCustomer $poscustomer)
    {
        //
    }


    public function destroy(PosCustomer $poscustomer)
    {
        //
    }
}
