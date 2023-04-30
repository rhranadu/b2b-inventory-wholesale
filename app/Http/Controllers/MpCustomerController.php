<?php

namespace App\Http\Controllers;

use App\Http\Requests\PosCustomer\MpCustomerPaymentStore;
use App\MarketplaceUser;
use App\MarketplaceUserAddress;
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

class MpCustomerController extends Controller
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
        $title = 'Marketplace Customer';
        $page_detail = 'List of Marketplace Customer';
        return view('mp_customers.all_list', compact('title', 'page_detail'));
    }

    public function getAllListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $mp_customers =array();
        $mp_customers_sale = Sale::select()->where('vendor_id', auth()->user()->vendor_id)->distinct()->groupBy('marketplace_user_id')->with('marketplaceUser')->latest()->get();
        if (!empty($mp_customers_sale)) {
            foreach ($mp_customers_sale as $key => $value) {
                if (!empty($value['marketplaceUser'])){
                    $mp_customers[$key] = $value['marketplaceUser'];
                }
            }
        }
        foreach ($mp_customers as &$mp_customer){
            $mp_customer->total_due = 0;
            $user_address = array();
            $user_addresss = MarketplaceUserAddress::where('marketplace_user_id',$mp_customer['id'])->get();
            if (!empty($user_addresss)) {
                foreach ($user_addresss as $key => $value) {
                    if ($value->is_default_address == 1) {
                        $user_address = $value;
                        break;
                    } else {
                        $user_address = $value;
                    }
                }
            }
            $mp_customer->address = $user_address->address_field_1 ?? '';
//            $mp_customer->address = $user_address->address_field_2 ? $mp_customer->address.' '.$user_address->address_field_2 : '';
            $sales = Sale::where('marketplace_user_id',$mp_customer['id'])->get();
            if (!empty($sales)) {
                foreach ($sales as $sale) {
                    $sale_payment = SalePayment::where('sale_id', $sale->id)->orderBy('id', 'desc')->first();
                    if (!empty($sale_payment)) {
                        $mp_customer->total_due += $sale_payment->due_amount;
                    }
                }
            }
        }
//        $mp_customerss = array();
//        foreach ($mp_customers as $key => &$mp_customer){
//            if (!empty($mp_customer->total_due)){
//                $mp_customerss[$key] = $mp_customer;
//            }
//        }
        return DataTables::of($mp_customers)
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
            ->addColumn('action', function ($mp_customer) {
                return '<div class="btn-group">
                        <a href="/admin/report/sale-wise-due/?mp_customer_id=' . $mp_customer['id'] . '"   class="btn btn-sm btn-icon btn-info" data-toggle="tooltip" data-placement="auto" title="Payment History" data-original-title="Payment History" target="_blank"><i class="fas fa-history"></i></a>
                        <a href="/admin/add/mp_customer_payment/' . $mp_customer['id'] . '" style="background: #00c292"  class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="auto" title="Submit a Payment" data-original-title="Submit a Payment"><i class="lab la-stripe-s"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deletePosCustomer('.$mp_customer['id'].')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="supplierDeleteForm-'.$mp_customer['id'].'" action="/admin/mp_customer/'. $mp_customer['id'] .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
//            <a href="/admin/poscustomer/' . $pos_customer->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>

            ->rawColumns(['status','image','action'])
            ->make(true);
    }

    public function addMpCustomerPayment(Request $request)
    {
        $sale_id = request()->has('sale_id') ? request()->query('sale_id') : 0;
        $mp_customer = MarketplaceUser::where('id',$request->mp_customer_id)->first();
        if(isset($mp_customer->name)){
            $title = $mp_customer->name.' | Submit Payment ';
            $page_detail = 'Submit a Payment for ' .$mp_customer->name ;
        }
//        if ($mp_customer->vendor_id != Auth::user()->vendor_id)
//        {
//            abort(404);
//        }
        $sale_data = array();
        if (!empty($sale_id)) {
            $sale = Sale::where('id', $sale_id)->first();
            $sale_payment = SalePayment::where('sale_id',$sale->id)->where('marketplace_user_id',$sale->marketplace_user_id)->where('warehouse_id','!=',0)->latest()->first();
            if (!empty($sale_payment)) {
                $sale_data['invoice_no'] = $sale->invoice_no;
                $sale_data['id'] = $sale->id;
                $sale_data['due_payment'] = $sale_payment->due_amount;
            }
        }
        return view('mp_customers.mp_customer_payment_submit', compact('mp_customer','title', 'page_detail','sale_id','sale_data'));
    }
    public function getMpCustomerInvoiceList(Request $request) {
        if($request->ajax()){
            $sale_id = request()->has('sale_id') ? request()->query('sale_id') : 0;
            $datas = Sale::query();
            if(!empty($sale_id)){
                $datas->where('id', $sale_id);
            }
            if(!empty(auth()->user()->vendor_id)){
                $datas->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty($request->mp_customer_id)){
                $datas->where('marketplace_user_id', $request->mp_customer_id);
            }
            if(!empty($request->mp_customer)){
                $datas->where('marketplace_user_id', $request->mp_customer);
            }
            $invoices = $datas->get();
            $data = [];
            foreach ($invoices as $value){
                $due_data = SalePayment::where('sale_id',$value->id)->where('marketplace_user_id',$value->marketplace_user_id)->where('warehouse_id','!=',0)->latest()->first();
                if (!empty($due_data) && (!empty(intval($due_data->due_amount)))) {
                    $data[$value->id] = $value->invoice_no . ' [Due: ' . $due_data->due_amount . ']';
                }
            }
            return response()->json($data, Response::HTTP_OK);
        }
    }

    public function addMpCustomerPaymentStore(MpCustomerPaymentStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->amount <= 0) {
                return redirect()->back()->with('error', 'Please insert valid amount!');
            }
            $vendor_id =  SalePayment::where([
                'marketplace_user_id' => $request->mp_customer_id,
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
                    'marketplace_user_id' => $request->mp_customer_id,
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
                    'marketplace_user_id' => $request->mp_customer_id,
                    'sale_id' => $sale->id,
                    'warehouse_id'=> $getSalesPaymentRow->warehouse_id,
                    'final_total'=>$getSalesPaymentRow->final_total,
                    'pay_amount' => $request->amount,
                    'due_amount' => $total_balance,
                    'payment_by' => $request->paymentMethod,
                    'bank_name' => $request->bank_name,
                    'branch_name' => $request->branch_name,
                    'bank_account_name' => $request->bank_account_name,
                    'account_no' => $request->account_no,
                    'transaction_no' => $request->transaction_no,
                    'swift_code' => $request->swift_code,
                    'mobile_service_name' => $request->mobile_service_name,
                    'check_no' => $request->cheque_no,
                    'cheque_date' => $request->cheque_date,
                    'transaction_date' => $request->transaction_date,
                    'card_name' => $request->card_name,
                    'card_number' => $request->card_number,
                    'payment_date' => $request->payment_date,
                    'status'=> $status,
                    'give_back'=> 0,
                    'comment' => $request->comment,
                    'created_by' => Auth::id(),
                ]);
            $image = $request->file('image');
            if ($image)
            {
                $path_info = getPathInfo(['markeplace_customers',$sale_payment->marketplace_user_id,'sale_payments',$sale_payment->id]);
                makeDirectory($path_info,0755);
                $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                $imageLocation = 'backend/uploads/markeplace_customers/'.$sale_payment->marketplace_user_id.'/sale_payments/'.$sale_payment->id.'/'.$setName;
                Image::make($image)->save($path_info.'/'.$setName, 100);
                $sale_payment->image = $imageLocation;
                $sale_payment->save();
            }
                $sale_update = Sale::where('id',$sale->id)->update(['status'=> $status]);

            }else if (!$request->sale_id){
                $sale = Sale::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'marketplace_user_id' => $request->mp_customer_id,
                ])->orderBy('id', 'asc')->get();
                $total_payable = 0;
                foreach ($sale as $key => $value){
                    $getSalesPaymentRow = SalePayment::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'marketplace_user_id' => $request->mp_customer_id,
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
                        'marketplace_user_id' => $request->mp_customer_id,
                        'sale_id' => $value->id,
                    ])->orderBy('id', 'desc')->first();

                    if ( intval($Amount_left) != 0 && $getSalesPaymentRow->due_amount < $Amount_left && !empty(intval($getSalesPaymentRow->due_amount))) {
                        $sale_payment = SalePayment::create([
                            'vendor_id' => $vendor_id,
                            'marketplace_user_id' => $request->mp_customer_id,
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
                            $path_info = getPathInfo(['markeplace_customers',$sale_payment->marketplace_user_id,'sale_payments',$sale_payment->id]);
                            makeDirectory($path_info,0755);
                            $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                            $imageLocation = 'backend/uploads/markeplace_customers/'.$sale_payment->marketplace_user_id.'/sale_payments/'.$sale_payment->id.'/'.$setName;
                            Image::make($image)->save($path_info.'/'.$setName, 100);
                            $sale_payment->image = $imageLocation;
                            $sale_payment->save();
                        }
                        $sale_update = Sale::where('id',$value->id)->update(['status'=>'FP']);
                        $Amount_left = $Amount_left - $getSalesPaymentRow->due_amount;
                    }elseif( intval($Amount_left) != 0 && $getSalesPaymentRow->due_amount > $Amount_left) {
                        $sale_payment = SalePayment::create([
                            'vendor_id' => $vendor_id,
                            'marketplace_user_id' => $request->mp_customer_id,
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
                            $path_info = getPathInfo(['markeplace_customers',$sale_payment->marketplace_user_id,'sale_payments',$sale_payment->id]);
                            makeDirectory($path_info,0755);
                            $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                            $imageLocation = 'backend/uploads/markeplace_customers/'.$sale_payment->marketplace_user_id.'/sale_payments/'.$sale_payment->id.'/'.$setName;
                            Image::make($image)->save($path_info.'/'.$setName, 100);
                            $sale_payment->image = $imageLocation;
                            $sale_payment->save();
                        }
                        $Amount_left = 0.00;
                    }
                }
            }
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
