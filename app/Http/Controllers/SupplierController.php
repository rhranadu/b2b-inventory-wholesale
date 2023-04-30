<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\SupplierAccount;
use App\SupplierPaymentTransaction;
use App\Http\Requests\Supplier\SupplierStore;
use App\Http\Requests\Supplier\SupplierUpdate;
use App\Http\Requests\SupplierPaymentMethod\PaymentMethodStore ;
use App\Http\Requests\SupplierPaymentMethod\PaymentMethodUpdate;
use App\Http\Requests\SupplierPaymentMethod\PaymentSubmitStore;
use App\Tax;
use App\Supplier;
use App\Purchase;
use App\Vendor;
use App\SupplierPaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class SupplierController extends Controller
{

    public function index()
    {
        $title = 'Supplier';
        $page_detail = 'List of Supplier';
        return view('suppliers.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $suppliers = Supplier::where('vendor_id', auth()->user()->vendor_id)->with('supplierAccount')->latest()->get();
        foreach ($suppliers as $supplier){
            $supplier->details = Str::limit($supplier->details,50);
        }
        return DataTables::of($suppliers)
            ->addIndexColumn()

            ->editColumn('status', function ($supplier) {
                if ($supplier->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$supplier->status.'" data_id="'.$supplier->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$supplier->status.'" data_id="'.$supplier->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($supplier) {
                if ($supplier->image)  return '
                        <div class="pop_img" data-img="'.asset($supplier->image).'">
                            <img width="50"
                                 src="'.asset($supplier->image).'"
                                 alt="image">
                        </div>
                ';
                return '
                        <div>
                            No Image
                        </div>
                ';
            })
            ->addColumn('action', function ($supplier) {
                return '<div class="btn-group">
                        <a href="/admin/add/payment_method/' . $supplier->id . '"   class="btn btn-sm btn-icon btn-primary" data-toggle="tooltip" data-placement="auto" title="Add Payment Method" data-original-title="Add Payment Method"><i class="las la-money-check-alt"></i></a>
                        <a href="/admin/add/supplier_payment/' . $supplier->id . '" style="background: #00c292"  class="btn btn-sm btn-icon btn-success" data-toggle="tooltip" data-placement="auto" title="Submit a Payment" data-original-title="Submit a Payment"><i class="lab la-stripe-s"></i></a>
                        <a href="/admin/supplier/' . $supplier->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteSupplier('.$supplier->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="supplierDeleteForm-'.$supplier->id.'" action="/admin/supplier/'. $supplier->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status','image','action'])
            ->make(true);

    }


    public function create()
    {
        $title = 'Create Supplier';
        $page_detail = 'Create a Supplier for your Vendor';
        return view('suppliers.create',compact('title', 'page_detail'));
    }

    public function store(SupplierStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
                if($request->name) {
                    $supplier_name = Supplier::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
                    if ($supplier_name){
                        return redirect()->back()->with('error', 'Duplicate supplier name found please insert another name!');
                    }
                }
                $request['status'] = $request->status ?? '0';

                $supplier =  Supplier::create([
                        'name' => $request->name,
                        'email' => $request->email,
                        'address' => $request->address,
                        'mobile' => $request->mobile,
                        'details' => $request->details,
                        'website' => $request->website,
                        'type' => $request->type,
                        'vendor_id' => Auth::user()->vendor_id,
                        'status' => $request->status,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);

                if ($request->hasFile('img')){

                    $rootImage = $request->file('img');
                    $extension = $request->file('img')->getClientOriginalExtension();

                    if (!in_array($extension, ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
                        return redirect()->back()->with('error', $extension . ' type image is not supported!');
                    }

                    $path_info = getPathInfo(['suppliers', 'images', 'original',$supplier->id]);
                    $thumbnail_path_info = getPathInfo(['suppliers', 'images', 'thumbnail',$supplier->id]);
                    makeDirectory($path_info,0755);
                    makeDirectory($thumbnail_path_info,0755);

                    $setName = $supplier->name . '-' . rand() . '-' . uniqid() . '.' .$rootImage->getClientOriginalExtension();
                    $original_imageLocation = 'backend/uploads/suppliers/images/original/'.$supplier->id.'/'.$setName;
                    Image::make($rootImage)->save($path_info.'/'.$setName, 100);
                    $image_url = URL::to('/'). '/' . $original_imageLocation;

                    // for thumbnail image

                    $thumbnail_imageLocation = 'backend/uploads/suppliers/images/thumbnail/' . $supplier->id . '/' . $setName;
                    Image::make($rootImage)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                    $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

                    $supplier->image = $original_imageLocation;
                    $supplier->image_url = $image_url;
                    $supplier->thumbnail_image = $thumbnail_imageLocation;
                    $supplier->thumbnail_image_url = $thumbnail_image_url;
                    $supplier->save();
                }
            // Commit Transaction
            DB::commit();
                return redirect()->action('SupplierController@index')->with('success', 'Supplier Created success');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function show(Supplier $supplier)
    {
        if($supplier->vendor_id == auth()->user()->vendor_id)
        {
            return view('suppliers.view', compact('supplier'));
        }else{
           abort(404);
        }
    }


    public function edit(Supplier $supplier)
    {
        $title = 'Edit Supplier';
        $page_detail = 'Edit Supplier for your Vendor';

        if($supplier->vendor_id == auth()->user()->vendor_id)
        {
            return view('suppliers.edit', compact('supplier','title', 'page_detail'));
        }else{
            abort(404);
        }

    }


    public function update(SupplierUpdate $request, Supplier $supplier)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->name) {
                $supplier_name = Supplier::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'name' => $request->name
                ])->where('id', '<>' , $supplier->id)
                    ->value('name');
                if ($supplier_name){
                    return redirect()->back()->with('error', 'Duplicate supplier name found please insert another name!');
                }
            }

            $request['status'] = $request->status ?? 0;

            $supplier->update([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'mobile' => $request->mobile,
                'details' => $request->details,
                'website' => $request->website,
                'status' => $request->status,
                'type' => $request->type,
                'vendor_id' => Auth::user()->vendor_id,
                'updated_by' => Auth::id(),
            ]);

            if ($request->hasFile('img')){
                if ($supplier->image)
                {
                    if (file_exists($supplier->image)){
                        unlink($supplier->image);
                        unlink($supplier->thumbnail_image);
                    }
                }
                $rootImage = $request->file('img');
                $extension = $request->file('img')->getClientOriginalExtension();
                if (!in_array($extension, ['jpeg', 'png', 'jpg', 'gif', 'svg'])) {
                    return redirect()->back()->with('error', $extension . ' type image is not supported!');
                }

                $path_info = getPathInfo(['suppliers', 'images', 'original',$supplier->id]);
                $thumbnail_path_info = getPathInfo(['suppliers', 'images', 'thumbnail',$supplier->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);
                $setName = $supplier->name . '-' . rand() . '-' . uniqid() . '.' .$rootImage->getClientOriginalExtension();
                $original_imageLocation = 'backend/uploads/suppliers/images/original/'.$supplier->id.'/'.$setName;
                Image::make($rootImage)->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;

                // for thumbnail image

                $thumbnail_imageLocation = 'backend/uploads/suppliers/images/thumbnail/' . $supplier->id . '/' . $setName;
                Image::make($rootImage)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

                $supplier->image = $original_imageLocation;
                $supplier->image_url = $image_url;
                $supplier->thumbnail_image = $thumbnail_imageLocation;
                $supplier->thumbnail_image_url = $thumbnail_image_url;
                $supplier->save();
            }
            // Commit Transaction
            DB::commit();
            return redirect()->action('SupplierController@index')->with('success', 'Supplier Updated success');
            } catch (Exception $exc) {

            // Rollback Transaction
        DB::rollback();

        return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function destroy(Supplier $supplier)
    {
        // only auth user and admin allow
        if($supplier->vendor_id == auth()->user()->vendor_id)
        {
            if ($supplier->image)
            {
                if (file_exists($supplier->image)){
                    unlink($supplier->image);
                }
            }

            if ($supplier->supplierPurchases->count() > 0 || $supplier->payments->count() > 0)
            {
                return redirect()->back()->with('warning', $supplier->name. ' not allow to delete');
            }

            $supplier->delete();
            return redirect()->back()->with('success', 'Supplier Deleted success');
        }else{
            abort(404);
        }
    }


    public function addBank(Supplier $supplier)
    {
        if($supplier->vendor_id == auth()->user()->vendor_id)
        {
            return view('suppliers.add_bank', compact('supplier'));
        }else{
            abort(404);
        }
    }


    public function addBankStore(Request $request, Supplier $supplier)
    {
        if($supplier->vendor_id == auth()->user()->vendor_id)
        {
            $request->validate([
                'account_for' => 'required|string',
                'account_owner_id' => 'required|integer',
                'bank_name' => 'required|string',
                'account_name' => 'required|string',
                'account_number' => 'required|string',
                'branch' => 'required',
                'img' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
            ]);

            $request['status'] = $request->status ??  0;
            $request['created_by'] = auth()->id();
            $request['updated_by'] = auth()->id();

            $check = BankAccount::where(['account_owner_id' => $supplier->id, 'account_for' => $supplier->type])->first();

            if ($check)
            {
                $check->update($request->except('_token'));
                return redirect()->back()->with('success', 'Bank  Account Updated  success');
            }
            BankAccount::create($request->except('_token'));
            return redirect()->back()->with('success', 'Bank  Account Added  success');
        }else{
            abort(404);
        }

    }


    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $supplier =  Supplier::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
        if ($supplier)
        {
            $supplier->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }

    }



    //start==> Add new payment methods for each supplier

    public function AddPaymentMethod(Supplier $supplier)
    {
        if(isset($supplier->name)){
            $title = $supplier->name.' | Payment Method';
            $page_detail = 'Create Payment Method for ' .$supplier->name ;
        }
        if ($supplier->vendor_id != Auth::user()->vendor_id)
        {
            abort(404);
        }
        $supplier_payment_methods =  SupplierPaymentMethod::where([
                'vendor_id' => Auth::user()->vendor_id,
                'supplier_id' => $supplier->id,
            ])
            ->paginate(5);

        return view('suppliers.add_payment_method', compact('supplier','supplier_payment_methods','title', 'page_detail'));
    }

    public function PaymentMethodStore(PaymentMethodStore $request, Supplier $supplier)
    {

        if ($supplier->vendor_id != Auth::user()->vendor_id)
        {
            abort(404);
        }
        DB::beginTransaction();
        try
        {
            if ($request->visible_name){
            //    $check_visible_name =   SupplierPaymentMethod::where([
            //        'supplier_id' => $request->account_owner_id,
            //        'visible_name' => $request->visible_name,
            //        'deleted_at' => NULL,
            //    ])->value('visible_name');
            //    if ($check_visible_name){
            //        return redirect()->back()->with('error', 'Duplicate Visible Name found !');
            //    }else{
            //        $visible_name = $request->visible_name;
            //    }
                $visible_name = $request->visible_name;
            }elseif($request->visible_name == null){
                    if ($request->payment_type == 'bank_transfer' || $request->payment_type == 'cheque' || $request->payment_type == 'online_banking' ){
                        $visible_name = $request->bank_name . ' | ' . $request->account_no ;
                    }elseif ($request->payment_type == 'mobile_banking'){
                        $visible_name = $request->mobile_service_name . ' | ' . $request->account_no ;
                    }
            }


            $request['status'] = $request->status ?? 0;

            SupplierPaymentMethod::create([
                'supplier_id' => $supplier->id,
                'vendor_id' => $supplier->vendor_id,
                'payment_type' => $request->payment_type,
                'account_no' => $request->account_no,
                'visible_name' => $visible_name,
                'bank_name' => $request->bank_name,
                'card_name' => $request->card_name,
                'card_number' => $request->card_number,
                'branch_name' => $request->branch_name,
                'bank_account_name' => $request->bank_account_name,
                'swift_code' => $request->swift_code,
                'mobile_service_name' => $request->mobile_service_name,
                'status' => $request->status,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            // Commit Transaction
            DB::commit();
            return redirect()->back()->with('success', 'Payment method successfully added');
        } catch (\Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }

    }

    public function editPaymentMethod($id)
    {
        if(isset($id)){
            $title =  'Edit Supplier Payment Method';
            $page_detail = 'Edit Payment Method';
        }
        $supplier_payment_method =  SupplierPaymentMethod::where([
            'id' => $id,
            'vendor_id' => Auth::user()->vendor_id,
        ])->first();
        // only allow auth user and admin
        if($supplier_payment_method->vendor_id == auth()->user()->vendor_id)
        {
            return view('suppliers.edit_payment_method', compact('supplier_payment_method','title', 'page_detail'));
        }else{
            abort(404);
        }
    }

    public function updatePaymentMethod(Request $request,$id)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            // only allow auth user and admin
            if($request->vendor_id == auth()->user()->vendor_id)
            {
                if ($request->visible_name){
                    $check_visible_name =   SupplierPaymentMethod::where([
                        'supplier_id' => $request->supplier_id,
                        'visible_name' => $request->visible_name,
                        'deleted_at' => NULL,
                    ])->where('id', '<>' , $id)->value('visible_name');
                    if ($check_visible_name){

                        return redirect()->back()->with('error', 'Duplicate Visible Name found !');
                    }else{
                        $visible_name = $request->visible_name;
                    }
                }elseif($request->visible_name == null){
                    if ($request->payment_type == 'bank_transfer' || $request->payment_type == 'cheque' || $request->payment_type == 'online_banking' ){
                        $visible_name = $request->bank_name . ' | ' . $request->account_no ;
                    }elseif ($request->payment_type == 'mobile_banking'){
                        $visible_name = $request->mobile_service_name . ' | ' . $request->account_no ;
                    }
                }

                $request['status'] = $request->status ?? 0;
                SupplierPaymentMethod::where('id', $id)->update([
                    'account_no' => $request->account_no,
                    'visible_name' => $visible_name,
                    'bank_name' => $request->bank_name,
                    'card_name' => $request->card_name,
                    'card_number' => $request->card_number,
                    'branch_name' => $request->branch_name,
                    'bank_account_name' => $request->bank_account_name,
                    'swift_code' => $request->swift_code,
                    'mobile_service_name' => $request->mobile_service_name,
                    'status' => $request->status,
                    'updated_by' => Auth::id(),
                ]);
                // Commit Transaction
                DB::commit();
                return redirect()->back()->with('success', 'Payment Method Updated Success !');
            }

        } catch (\Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }


    }

    public function PaymentMethodDestroy(Request $request,$id)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            // $payment_method_id = $request->val_id;
            $checkPaymentMethod = SupplierPaymentMethod::where('id', $id)->first();
            if ($checkPaymentMethod){
            // only allow auth user and admin
                if($checkPaymentMethod->vendor_id == auth()->user()->vendor_id)
                {
                    $checkPaymentMethod->delete();
                    // Commit Transaction
                    DB::commit();
                    return redirect()->back()->with('success', 'Payment method deleted success !');
                }
            }
        } catch (\Exception $exc) {

            // Rollback Transaction
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }

    }

    public function activeUnactivePaymentMethod(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $supplierPaymentMethod = SupplierPaymentMethod::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
            if ($supplierPaymentMethod)
            {
                $supplierPaymentMethod->update(['status' => $request->setStatus]);
                // Commit Transaction
                DB::commit();

                return response()->json('true');
            }
        } catch (\Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }


      //start==> New payment submit for each supplier


    public function addSupplierPayment(Supplier $supplier)
    {

        if(isset($supplier->name)){
            $title = $supplier->name.' | Submit Payment ';
            $page_detail = 'Submit a Payment for ' .$supplier->name ;
        }
        if ($supplier->vendor_id != Auth::user()->vendor_id)
        {
            abort(404);
        }
        $supplier_payment_methods =  SupplierPaymentMethod::where([
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $supplier->id,
            'status' => 1,
        ])->get();
        return view('suppliers.supplier_payment_submit', compact('supplier','supplier_payment_methods','title', 'page_detail'));
    }

    public function getPurchaseInvoiceList(Request $request) {
        if($request->ajax()){
            $data = Purchase::query();
            if(!empty(auth()->user()->vendor_id)){
                $data->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty($request->supplier_id)){
                $data->where('supplier_id', $request->supplier_id);
            }
            if(!empty($request->search)){
                $data->where('invoice_no','like', '%'.$request->search.'%');
            }
            $invoices = $data->pluck('invoice_no','id')->toArray();
            $data = [];
            foreach ($invoices as $key => $value){
                $due_data = SupplierPaymentTransaction::where('purchase_id',$key)->orderBY('id','desc')->first();
                if (!empty($due_data) && (!empty(intval($due_data->balance)))) {
                    $data[$key] = $value . ' [Due: ' . $due_data->balance . ']';
                }
            }
            return response()->json($data, Response::HTTP_OK);
        }
    }

//    public function addSupplierPaymentStore(PaymentSubmitStore $request)
//    {
//
//        // Begin Transaction
//        DB::beginTransaction();
//        try
//        {
//            if($request->amount <= 0) {
//                return redirect()->back()->with('error', 'Please insert valid amount!');
//            }
//            $vendor_id =  SupplierPaymentMethod::where([
//                'supplier_id' => $request->supplier_id,
//            ])->value('vendor_id');
//            if ($vendor_id != Auth::user()->vendor_id)
//            {
//                abort(404);
//            }
//            $getEverySuppliersRows = SupplierPaymentTransaction::where([
//                'vendor_id' => Auth::user()->vendor_id,
//                'supplier_id' => $request->supplier_id,
//            ])->get();
//            $last_record = $getEverySuppliersRows->last();
//            if (empty($getEverySuppliersRows)){
//                $total_balance = -($request->amount);
//                $total_balance = number_format($total_balance, 2,'.','');
//            }else{
//                if (isset($last_record->balance)){
//                    $total_balance = ($last_record->balance - $request->amount);
//                    $total_balance = number_format($total_balance, 2,'.','');
//                }
//                else{
//                    $total_balance = 0;
//                }
//            }
//            if ($request->transaction_date){
//                $transaction_date = $request->transaction_date;
//            }else{
//                $transaction_date = $request->payment_date;
//            }
//            $purchase = Purchase::find($request->purchase_id);
//            $supplier_payment_transaction = SupplierPaymentTransaction::create([
//                'vendor_id' => $vendor_id,
//                'supplier_id' => $request->supplier_id,
//                'purchase_id' => $purchase->id,
//                'purchase_invoice_no' => $purchase->invoice_no,
//                'debit' => $request->amount,
//                'balance' => $total_balance,
//                'supplier_payment_method_id' => $request->supplier_payment_method_id,
//                'cheque_no' => $request->cheque_no,
//                'cheque_date' => $request->cheque_date,
//                'transaction_no' => $request->transaction_no,
//                'transaction_date' => $transaction_date,
//                'payment_date' => $request->payment_date,
//                'note' => $request->note,
//                'particulars' => $request->particulars,
//                'created_by' => Auth::id(),
//            ]);
//
//            $image = $request->file('image');
//            if ($image)
//            {
//                $path_info = getPathInfo(['suppliers',$request->supplier_id,'supplier_payment_transactions',$supplier_payment_transaction->id]);
//                makeDirectory($path_info,0755);
//                $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
//                $imageLocation = 'backend/uploads/suppliers/'.$request->supplier_id.'/supplier_payment_transactions/'.$supplier_payment_transaction->id.'/'.$setName;
//                Image::make($image)->save($path_info.'/'.$setName, 100);
//                $supplier_payment_transaction->image = $imageLocation;
//                $supplier_payment_transaction->save();
//            }
//
//            $getSupplierAccount = SupplierAccount::where([
//                'vendor_id' => Auth::user()->vendor_id,
//                'supplier_id' => $request->supplier_id,
//            ])->first();
//
//            if ($getSupplierAccount){
//                SupplierAccount::where([
//                    'vendor_id' => Auth::user()->vendor_id,
//                    'supplier_id' => $request->supplier_id,
//                ])->update(['balance' => $total_balance,'updated_by' => Auth::id()]);
//
//            }else{
//                SupplierAccount::create([
//                    'supplier_id' => $request->supplier_id,
//                    'vendor_id' => Auth::user()->vendor_id,
//                    'balance' => $total_balance,
//                    'created_by' => Auth::id(),
//                ]);
//            }
//
//            // Commit Transaction
//            DB::commit();
//
//            return redirect()->back()->with('success', 'Successfully Payment done!');
//
//        } catch (\Exception $exc) {
//
//            // Rollback Transaction
//            DB::rollback();
//
//            return redirect()->back()->with('error', $exc->getMessage());
//        }
//    }
    public function addSupplierPaymentStore(PaymentSubmitStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {

            if ($request->transaction_no_optional != null){
                $request->transaction_no = $request->transaction_no_optional;
            }

            if($request->amount <= 0) {
                return redirect()->back()->with('error', 'Please insert valid amount!');
            }
            $vendor_id =  SupplierPaymentMethod::where([
                'supplier_id' => $request->supplier_id,
            ])->value('vendor_id');
            if ($vendor_id != Auth::user()->vendor_id)
            {
                abort(404);
            }
            if ($request->purchase_id) {
                $purchase = Purchase::find($request->purchase_id);
                if (empty($purchase)){
                    return redirect()->back()->with('error', "Invalid Invoice");
                }
                $getSuppliersRow = SupplierPaymentTransaction::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'supplier_id' => $request->supplier_id,
                    'purchase_id' => $request->purchase_id,
                ])->orderBy('id','desc')->first();
                if (empty($getSuppliersRow)){
                    return redirect()->back()->with('error', "Invalid Invoice");
                }
                if ($getSuppliersRow->balance < $request->amount){
                    return redirect()->back()->with('error', "Can't Pay More than Invoice Due");
                }

                if (isset($getSuppliersRow->balance)) {
                    $total_balance = ($getSuppliersRow->balance - $request->amount);
                    $total_balance = number_format($total_balance, 2, '.', '');
                } else {
                    $total_balance = 0;
                }

                if ($request->transaction_date) {
                    $transaction_date = $request->transaction_date;
                } else {
                    $transaction_date = $request->payment_date;
                }
                $supplier_payment_transaction = SupplierPaymentTransaction::create([
                    'vendor_id' => $vendor_id,
                    'supplier_id' => $request->supplier_id,
                    'purchase_id' => $purchase->id,
                    'purchase_invoice_no' => $purchase->invoice_no,
                    'credit' => $request->amount,
                    'balance' => $total_balance,
                    'supplier_payment_method_id' => $request->supplier_payment_method_id,
                    'cheque_no' => $request->cheque_no,
                    'cheque_date' => $request->cheque_date,
                    'transaction_no' => $request->transaction_no,
                    'transaction_date' => $transaction_date,
                    'payment_date' => $request->payment_date,
                    'note' => $request->note,
                    'particulars' => $request->particulars,
                    'created_by' => Auth::id(),
                ]);
                $image = $request->file('image');
                if ($image) {
                    $path_info = getPathInfo(['suppliers', $request->supplier_id, 'supplier_payment_transactions', $supplier_payment_transaction->id]);
                    makeDirectory($path_info, 0755);
                    $setName = rand() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imageLocation = 'backend/uploads/suppliers/' . $request->supplier_id . '/supplier_payment_transactions/' . $supplier_payment_transaction->id . '/' . $setName;
                    Image::make($image)->save($path_info . '/' . $setName, 100);
                    $supplier_payment_transaction->image = $imageLocation;
                    $supplier_payment_transaction->save();
                }

                $getSupplierAccount = SupplierAccount::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'supplier_id' => $request->supplier_id,
                ])->first();

                if ($getSupplierAccount) {
                    SupplierAccount::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'supplier_id' => $request->supplier_id,
                    ])->update(['balance' => number_format(($getSupplierAccount->balance - $request->amount),2, '.', ''), 'updated_by' => Auth::id()]);

                } else {
                    SupplierAccount::create([
                        'supplier_id' => $request->supplier_id,
                        'vendor_id' => Auth::user()->vendor_id,
                        'balance' => $total_balance,
                        'created_by' => Auth::id(),
                    ]);
                }
            }else if (!$request->purchase_id){
                $purchase = Purchase::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'supplier_id' => $request->supplier_id,
                ])->orderBy('id', 'asc')->get();
                $total_payable = 0;
                foreach ($purchase as $key => $value){
                    $getSuppliersRow = SupplierPaymentTransaction::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'supplier_id' => $request->supplier_id,
                        'purchase_id' => $value->id,
                    ])->orderBy('id', 'desc')->first();
                    $total_payable += $getSuppliersRow->balance;
                }
                if ($request->amount > $total_payable){
                    return redirect()->back()->with('error', "Can't Pay More than Total Due");
                }
                $Amount_left = $request->amount;
                foreach ($purchase as $key => $value){
                    $getSuppliersRow = SupplierPaymentTransaction::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'supplier_id' => $request->supplier_id,
                        'purchase_id' => $value->id,
                    ])->orderBy('id', 'desc')->first();
                    if ($request->transaction_date) {
                        $transaction_date = $request->transaction_date;
                    } else {
                        $transaction_date = $request->payment_date;
                    }
                    if ( intval($Amount_left) != 0 && $getSuppliersRow->balance < $Amount_left) {
                        $supplier_payment_transaction = SupplierPaymentTransaction::create([
                            'vendor_id' => $vendor_id,
                            'supplier_id' => $request->supplier_id,
                            'purchase_id' => $value->id,
                            'purchase_invoice_no' => $value->invoice_no,
                            'credit' => number_format(($getSuppliersRow->balance), 2, '.', ''),
                            'balance' => 0.00,
                            'supplier_payment_method_id' => $request->supplier_payment_method_id,
                            'cheque_no' => $request->cheque_no,
                            'cheque_date' => $request->cheque_date,
                            'transaction_no' => $request->transaction_no,
                            'transaction_date' => $transaction_date,
                            'payment_date' => $request->payment_date,
                            'note' => $request->note,
                            'particulars' => $request->particulars,
                            'created_by' => Auth::id(),
                        ]);
                        $image = $request->file('image');
                        if ($image) {
                            $path_info = getPathInfo(['suppliers', $request->supplier_id, 'supplier_payment_transactions', $supplier_payment_transaction->id]);
                            makeDirectory($path_info, 0755);
                            $setName = rand() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                            $imageLocation = 'backend/uploads/suppliers/' . $request->supplier_id . '/supplier_payment_transactions/' . $supplier_payment_transaction->id . '/' . $setName;
                            Image::make($image)->save($path_info . '/' . $setName, 100);
                            $supplier_payment_transaction->image = $imageLocation;
                            $supplier_payment_transaction->save();
                        }
                        $Amount_left = $Amount_left - $getSuppliersRow->balance;
                    }elseif( intval($Amount_left) != 0 && $getSuppliersRow->balance > $Amount_left) {
                        $supplier_payment_transaction = SupplierPaymentTransaction::create([
                            'vendor_id' => $vendor_id,
                            'supplier_id' => $request->supplier_id,
                            'purchase_id' => $value->id,
                            'purchase_invoice_no' => $value->invoice_no,
                            'credit' => number_format(($Amount_left), 2, '.', ''),
                            'balance' => number_format(($getSuppliersRow->balance - $Amount_left), 2, '.', ''),
                            'supplier_payment_method_id' => $request->supplier_payment_method_id,
                            'cheque_no' => $request->cheque_no,
                            'cheque_date' => $request->cheque_date,
                            'transaction_no' => $request->transaction_no,
                            'transaction_date' => $transaction_date,
                            'payment_date' => $request->payment_date,
                            'note' => $request->note,
                            'particulars' => $request->particulars,
                            'created_by' => Auth::id(),
                        ]);
                        $image = $request->file('image');
                        if ($image) {
                            $path_info = getPathInfo(['suppliers', $request->supplier_id, 'supplier_payment_transactions', $supplier_payment_transaction->id]);
                            makeDirectory($path_info, 0755);
                            $setName = rand() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                            $imageLocation = 'backend/uploads/suppliers/' . $request->supplier_id . '/supplier_payment_transactions/' . $supplier_payment_transaction->id . '/' . $setName;
                            Image::make($image)->save($path_info . '/' . $setName, 100);
                            $supplier_payment_transaction->image = $imageLocation;
                            $supplier_payment_transaction->save();
                        }
                        $Amount_left = 0.00;
                    }
                }
//                dd($total_payable);
//                dd($supplier_payment_transaction);
//                $getSuppliersRow = SupplierPaymentTransaction::where([
//                    'vendor_id' => Auth::user()->vendor_id,
//                    'supplier_id' => $request->supplier_id,
//                    'purchase_id' => $request->purchase_id,
//                ])->orderBy('id', 'desc')->first();
//                if ($getSuppliersRow->balance < $request->amount) {
//                    return redirect()->back()->with('error', "Pay Amount can't be greater than Invoice Due");
//                }

//                if (empty($getSuppliersRow)) {
//                    $total_balance = -($request->amount);
//                    $total_balance = number_format($total_balance, 2, '.', '');
//                } else {
//                    if (isset($getSuppliersRow->balance)) {
//                        $total_balance = ($getSuppliersRow->balance - $request->amount);
//                        $total_balance = number_format($total_balance, 2, '.', '');
//                    } else {
//                        $total_balance = 0;
//                    }
//                }
//                if ($request->transaction_date) {
//                    $transaction_date = $request->transaction_date;
//                } else {
//                    $transaction_date = $request->payment_date;
//                }
//                $purchase = Purchase::find($request->purchase_id);
//                $supplier_payment_transaction = SupplierPaymentTransaction::create([
//                    'vendor_id' => $vendor_id,
//                    'supplier_id' => $request->supplier_id,
//                    'purchase_id' => $purchase->id,
//                    'purchase_invoice_no' => $purchase->invoice_no,
//                    'credit' => $request->amount,
//                    'balance' => $total_balance,
//                    'supplier_payment_method_id' => $request->supplier_payment_method_id,
//                    'cheque_no' => $request->cheque_no,
//                    'cheque_date' => $request->cheque_date,
//                    'transaction_no' => $request->transaction_no,
//                    'transaction_date' => $transaction_date,
//                    'payment_date' => $request->payment_date,
//                    'note' => $request->note,
//                    'particulars' => $request->particulars,
//                    'created_by' => Auth::id(),
//                ]);
                $image = $request->file('image');
                if ($image) {
                    $path_info = getPathInfo(['suppliers', $request->supplier_id, 'supplier_payment_transactions', $supplier_payment_transaction->id]);
                    makeDirectory($path_info, 0755);
                    $setName = rand() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imageLocation = 'backend/uploads/suppliers/' . $request->supplier_id . '/supplier_payment_transactions/' . $supplier_payment_transaction->id . '/' . $setName;
                    Image::make($image)->save($path_info . '/' . $setName, 100);
                    $supplier_payment_transaction->image = $imageLocation;
                    $supplier_payment_transaction->save();
                }

                $getSupplierAccount = SupplierAccount::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'supplier_id' => $request->supplier_id,
                ])->first();

                if ($getSupplierAccount) {
                    SupplierAccount::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'supplier_id' => $request->supplier_id,
                    ])->update(['balance' => number_format(($getSupplierAccount->balance - $request->amount), 2, '.', ''), 'updated_by' => Auth::id()]);

                } else {
                    SupplierAccount::create([
                        'supplier_id' => $request->supplier_id,
                        'vendor_id' => Auth::user()->vendor_id,
                        'balance' => number_format(($total_payable - $request->amount), 2, '.', ''),
                        'created_by' => Auth::id(),
                    ]);
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

    public function supplierList()
    {
        if (request()->ajax()) {
            $query = Supplier::query();
            if (!empty(request()->supplier_id)) {
                $query->where('id', auth()->user()->warehouse_id);
            } else {
                if (!empty(trim(request()->search))) {
                    $query->where('name', 'like', '%' . trim(request()->search) . '%');
                }
                if (!empty(auth()->user()->vendor_id)) {
                    $query->where('vendor_id', auth()->user()->vendor_id);
                }
            }
            $query->where('status', 1);
            $data = $query->pluck('name','id')->toArray();
            return response()->json($data, Response::HTTP_OK);
        }
    }

}
