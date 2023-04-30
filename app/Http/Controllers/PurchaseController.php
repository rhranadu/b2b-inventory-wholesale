<?php

namespace App\Http\Controllers;


use App\ProductPoolStockDetail;
use App\PurchaseAdditionalExpenses;
use App\Http\Requests\Purchase\PurchaseStore;
use App\Product;
use App\ProductAttribute;
use App\ProductAttributeMap;
use App\Purchase;
use App\PurchaseAttributeDetails;
use App\PurchaseDetail;
use App\StockDetail;
use App\Supplier;
use App\ProductPool;
use App\ProductPoolPurchaseDetail;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
class PurchaseController extends Controller
{
    public function index()
    {
        $title = 'Product Purchase Orders';
        $page_detail = 'List of Product Purchase Orders for your Vendor';
        return view('purchase.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $purchases = Purchase::with('purchaseSupplier','purchaseDetail')->where('vendor_id', auth()->user()->vendor_id)->latest()->get();

        foreach ($purchases as $purchase){
            if ($purchase->purchaseDetail){
                $total_rcv = array();
                foreach ($purchase->purchaseDetail as $purchase_detail){
                    $total_rcv [] = $purchase_detail->stockWarehouse->sum('quantity');
                }
                $purchase->total_rcv =  array_sum($total_rcv);

                $purchase->purchaseDetail_count = $purchase->purchaseDetail->count();
                $purchase->purchaseDetail_quantity = $purchase->purchaseDetail->sum('quantity');

            }
        }

        return DataTables::of($purchases)
            ->addIndexColumn()

            ->editColumn('status', function ($purchase) {
                if ($purchase->status == 'draft') return '<span class="badge badge-danger" >Draft</span>';
                elseif ($purchase->status == 'posted') return '<span class="badge badge-primary">Posted</span>';
                elseif ($purchase->status == 'FR') return '<span class="badge badge-success" >Full Received</span>';
                elseif ($purchase->status == 'NY') return '<span class="badge badge-danger" >Not Yet</span>';
                return ' <span class="badge badge-warning" >Partial Received</span>';
            })
            ->addColumn('action', function ($purchase) {

                if ($purchase->status == 'draft') return '<div class="btn-group">
                        <a href="/admin/purchase/' . $purchase->id . '"   class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"data-placement="auto" title="VIEW" data-original-title="VIEW"><i class="fa fa-eye"></i></a>
                        <a href="/admin/purchases/' . $purchase->id . '/post-status" class="btn btn-sm btn-primary waves-effect" data-toggle="tooltip" data-placement="auto" title="Want to Post" data-original-title="Want to Post">Post</a>
                        <a href="/admin/purchase/' . $purchase->id . '/edit" class="btn btn-sm btn-warning waves-effect btn-icon" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-danger waves-effect btn-icon" onclick="deletePurchases('.$purchase->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deletePurchasesForm-'.$purchase->id.'" action="/admin/purchase/'. $purchase->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
                elseif ($purchase->status != 'FR') return '<div class="btn-group">
                        <a href="/admin/purchase/' . $purchase->id . '"   class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"data-placement="auto" title="VIEW" data-original-title="VIEW"><i class="fa fa-eye"></i></a>
                        <a href="/admin/purchases/' . $purchase->id . '/stock" style="background: #00c292; color: #f0f0f0" class="btn btn-sm waves-effect" data-toggle="tooltip" data-placement="auto" title="Receive your order" data-original-title="Receive your order">Order Receive</a>
                        </div>';
                return '<div class="btn-group">
                        <a href="/admin/purchase/' . $purchase->id . '"   class="btn btn-sm btn-info waves-effect" data-toggle="tooltip"data-placement="auto" title="VIEW" data-original-title="VIEW"><i class="fa fa-eye"></i></a>
                        </div>';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }
    public function create()
    {
        $title = 'Create Purchase';
        $page_detail = 'Create a Purchase for your vendor';
        $products = Product::where('vendor_id', auth()->user()->vendor_id)->active()->get();
        $attributes = ProductAttribute::where('vendor_id', auth()->user()->vendor_id)->active()->get();
        $suppliers = Supplier::where('vendor_id', auth()->user()->vendor_id)->active()->get();
        $attribute_maps = ProductAttributeMap::where('vendor_id', auth()->user()->vendor_id)->get();
        return view('purchase.create', compact('products','attributes', 'attribute_maps','suppliers','title', 'page_detail'));
    }


    public function store(PurchaseStore $request)
    {

        $data = $request->all();
        DB::transaction(function () use ($data){
            $purchase = $this->_storePurchase($data);
            $this->_storePurchaseDetails($data,$purchase);
        });

        return redirect()->action('PurchaseController@index')->with('success', 'Purchase Created Success!');
    }
    private function _storePurchase($data)
    {
        return Purchase::create([
            'vendor_id'    => auth()->user()->vendor_id,
            'supplier_id'     => $data['supplier_id'],
            'invoice_no' => $data['invoice_no'],
            'date'          => $data['date'],
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

    }
    public function edit(Purchase $purchase)
    {
        $title = 'Edit Purchase Order';
        $page_detail = 'Edit a Purchase Order of your vendor';

        if (auth()->user()->vendor_id == $purchase->vendor_id)
        {
            // if status is a draft so now we can access to edit
            if ($purchase->status === 'draft')
            {
                //  if use is main admin so he can edit all purchases
                $products = Product::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $attributes = ProductAttribute::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $suppliers = Supplier::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $attribute_maps = ProductAttributeMap::where('vendor_id', auth()->user()->vendor_id)->get();
                return view('purchase.edit_new', compact('purchase','products','attributes', 'attribute_maps','suppliers','title', 'page_detail'));
            }else{
                return redirect()->back()->with('warning', 'Edit  not allow!');
            }

        }else{
            return redirect()->route('admin.purchase.index')->with('warning', 'Wrong url');
        }

    }

    public function update(Request $request, Purchase $purchase)
    {
        if (Auth::user()->vendor_id !== $purchase->vendor_id && Auth::user()->user_type_id != 1)
        {
            return redirect()->action('PurchaseController@index')->with('error', 'Not Authorized!');
        }
        try{
            $data = $request->all();
            DB::transaction(function () use ($data,$request,$purchase){
                $purchase->update([
                'vendor_id'        => Auth::user()->vendor_id,
                'supplier_id'         => $request->supplier_id,
                'invoice_no'        => $request->invoice_no,
                'date'              => $request->date,
                'updated_by'        => Auth::id(),
                ]);
                $this->_deleteOldDraftDataDuringUpdate($request->purchase_id);
                $this->_storePurchaseDetails($data,$purchase);
            });
            return redirect()->action('PurchaseController@index')->with('success', 'Purchase Update Success!');
        }catch (\Exception $ex){
            return redirect()->back()->with('error',$ex->getMessage());
        }

    }

    private function _deleteOldDraftDataDuringUpdate($purchase_id){

        $purchase_detail_ids = PurchaseDetail::where('purchase_id',$purchase_id)->pluck('id');
        PurchaseDetail::where('purchase_id',$purchase_id)->delete();
        PurchaseAttributeDetails::whereIn('purchase_detail_id',$purchase_detail_ids)->delete();
    }


    public function updateOld(Request $request, Purchase $purchase)
    {
        if (Auth::user()->vendor_id !== $purchase->vendor_id && Auth::user()->user_type_id != 1)
        {
            return redirect()->action('PurchaseController@index')->with('error', 'Not Authorized!');
        }
        $purchase->update([
            'vendor_id'        => Auth::user()->vendor_id,
            'supplier_id'         => $request->supplier_id,
            'invoice_no'        => $request->invoice_no,
            'date'              => $request->date,
            'updated_by'        => Auth::id(),
        ]);
        foreach ($request->attribute_id as $key => $attribute_id)
        {
           $check_unique_row = PurchaseDetail::where([
                'product_id' => $request->product_id[$key],
                'purchase_id' => $purchase->id,
            ])->first();
            if ($check_unique_row)
            {
                $check_unique_row->quantity = $request->quantity[$key];
                $check_unique_row->save();
            } else {
                PurchaseDetail::create([
                    'purchase_id'       => $purchase->id,
                    'vendor_id'     => Auth::user()->vendor_id,
                    'product_id'       => $request->product_id[$key],
                    'quantity'         => $request->quantity[$key],
                    ]);
            }
        }
        return redirect()->action('PurchaseController@index')->with('success', 'Purchase Updated!');
    }

    private function _storePurchaseDetails($data,$purchase){
        foreach ($data['store_product_id'] as $key => $value)
        {
            $purchaseDetail = PurchaseDetail::create([
                'purchase_id'       => $purchase->id,
                'vendor_id'        => Auth::user()->vendor_id,
                'product_id'        => $value,
                'quantity'          => $data['store_quantity'][$key],
                'price'          => $data['store_price'][$key],
            ]);
            $this->_storePurchaseAttributeDetails($purchaseDetail->id,$data['store_attribute_id'][$key],$data['store_product_attribute_map_id'][$key]);
        }
    }

    private function _storePurchaseAttributeDetails($purchase_detail_id,$attribute_ids,$attribute_map_ids){

        $attribute_id_list = explode(',', $attribute_ids);
        $attribute_map_list = explode(',', $attribute_map_ids);
        foreach ($attribute_id_list as $index => $attribute_id){
            $attribute_info = ProductAttribute::where('id', $attribute_id)->select('name')->first();
            $attribute_maps_info = ProductAttributeMap::where('id', $attribute_map_list[$index])->select('value')->first();
            PurchaseAttributeDetails::create([
                'purchase_detail_id' => $purchase_detail_id,
                'attribute_id' => $attribute_id,
                'product_attribute_map_id' => $attribute_map_list[$index],
                'attribute_name' => $attribute_info['name'],
                'attribute_map_name' => $attribute_maps_info['value'],
            ]);
        }

    }
    private function _storeProductPool($purchase){

        foreach ($purchase->purchaseDetail as $pd) {
            // dump($pd);
            $attributeMapIds = [];
            foreach ($pd->purchaseAttributeDetails as $att) {
                $attributeMapIds[] = $att->product_attribute_map_id;
            }
            $attributeMapIds = explode(',', implode(',',$attributeMapIds));
            sort($attributeMapIds);
            // dump($attributeMapIds);
            $pool = ProductPool::where([['vendor_id', auth()->user()->vendor_id],['product_id',$pd->product_id]])->whereJsonLength('attribute_map_id', count($attributeMapIds))->whereJsonContains('attribute_map_id', $attributeMapIds)->first();
            if(empty($pool)){
                $pool = new ProductPool();
            }
            $pool->product_id = $pd->product_id;
            $pool->vendor_id = auth()->user()->vendor_id;
            $pool->attribute_map_id = $attributeMapIds;
            $pool->purchase_quantity = !empty($pool->purchase_quantity) ? $pool->purchase_quantity + $pd->quantity : $pd->quantity;
            $pool->created_by = auth()->user()->id;
            $pool->updated_by = auth()->user()->id;
            $pool->save();

            $purchaseDetailPool = new ProductPoolPurchaseDetail();
            $purchaseDetailPool->product_pool_id = $pool->id;
            $purchaseDetailPool->purchase_detail_id = $pd->id;
            $purchaseDetailPool->save();
        }
    }


    public function destroy(Purchase $purchase)
    {
        if (auth()->user()->user_type_id == 1 || auth()->user()->vendor_id == $purchase->vendor_id)
        {
            // if status is a draft so now we can access to delete
            if ($purchase->status === 'draft')
            {
                if ($purchase->purchaseProductStock->count() > 0)
                {
                    return redirect()->back()->with('warning', $purchase->name. ' not allow to delete');
                }
                $purchase->delete();
                return redirect()->back()->with('success', 'Purchase Deleted Success!');
            }else{
                return redirect()->back()->with('warning', 'Delete not allow!');
            }
        }else{
            return redirect()->route('admin.purchase.index')->with('warning', 'Wrong url');
        }
    }



    public function purchsesPostStatus(Purchase $purchase)
    {
        if (auth()->user()->vendor_id == $purchase->vendor_id)
        {
            if ($purchase->status === 'draft')
            {
                $purchase->status = 'posted';
                $purchase->save();
                $this->_storeProductPool($purchase);
                return redirect()->route('admin.purchase.index')->with('success', 'Purchases status changed');
            }else{
                return redirect()->route('admin.purchase.index')->with('warning', 'Purchases status alredy changed');
            }
        }else{
            return redirect()->route('admin.purchase.index')->with('warning', 'Wrong url');
        }

    }


    public function purchsesStock(Purchase $purchase)
    {
        $title = 'Purchases Stock form';
        $page_detail = 'Details of product purchase stock for your Vendor';
        if (auth()->user()->vendor_id == $purchase->vendor_id)
        {

            if ($purchase->status != 'draft' && $purchase->status !='FR')
            {
                //  if use is main admin so he can edit all purchases
                $products = Product::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $attributes = ProductAttribute::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $warehouses = Warehouse::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $suppliers = Supplier::where('vendor_id', auth()->user()->vendor_id)->active()->get();
                $attribute_maps = ProductAttributeMap::where('vendor_id', auth()->user()->vendor_id)->get();
                $purchase_additional_expenses = PurchaseAdditionalExpenses::where(['vendor_id' => Auth::user()->vendor_id, 'purchase_id' => $purchase->id])->get();

                return view('purchase.stock', compact('purchase','products','purchase_additional_expenses','attributes', 'attribute_maps','suppliers','warehouses','title', 'page_detail'));
            }else{
                return redirect()->back()->with('warning', 'This item not allow to stock!');
            }
        }else{
            return redirect()->route('admin.purchase.index')->with('warning', 'Wrong url');
        }
    }

    public function show(Purchase $purchase)
    {
        $title = 'Product Purchase';
        $page_detail = 'Details of product purchase for your Vendor';

        if (auth()->user()->vendor_id == $purchase->vendor_id)
        {
            foreach ($purchase->purchaseDetail as &$val) {
                foreach ($val->stockWarehouse as &$value) {
                    $stock_detail_ids = StockDetail::select('id')->where('purchase_detail_id', $value->purchase_detail_id)->get();
                    $stockDetailIds = array();
                    foreach ($stock_detail_ids as $ids) {
                        $stockDetailIds[$ids->id] = $ids->id;
                    }
                    $p_p_s_ds = ProductPoolStockDetail::whereIn('stock_detail_id', $stockDetailIds)->get();
                    $available_quantity = 0;
                    foreach ($p_p_s_ds as $p_p_s_d) {
                        $available_quantity += $p_p_s_d->available_quantity;
                    }
                    $value->available_quantity = $available_quantity;
                }
            }
            return view('purchase.view', compact('purchase', 'title', 'page_detail'));
        }else{
           return redirect()->route('admin.purchase.index')->with('warning', 'Wrong url');
        }
    }


    public function purchaseDetailProductDelete(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $delete = PurchaseDetail::where('id', $request->id)->first();
        if ($delete)
        {
            $delete->delete();
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }

    public function getSupplierDetails(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $supplier = Supplier::where('id', $request->supplier_id)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->first();


        if ($supplier)
        {
            $sendResult = '<div class="row">
                           <div class="col-lg-12 col-md-6 col-sm-6 col-xs-12" style="margin-top: 25px;">
                                <div class="invoice-cmp-ds">
                                    <div class="invoice-frm">
                                        <span>Supplier Details</span>
                                    </div>
                                    <div class="comp-tl">
                                        <h2>'.$supplier->name.'</h2>
                                        <p>'.$supplier->mobile.'</p>
                                        <p>'.$supplier->email.'</p>
                                        <p>'.$supplier->address.'</p>
                                    </div>
                                </div>
                            </div>
                        </div>';
            return $sendResult;
        }else{
            return response()->json('false');
        }


    }

    public function checkInvoiceNoUnique(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $invoiceUnique = Purchase::where(['supplier_id' => $request->supplier_id, 'invoice_no' => $request->invoice_no])->first();
        if ($invoiceUnique)
        {
            $year = date('Y', strtotime($invoiceUnique->date));
            if ($year === $request->year)
            {
                return response()->json('true');
            }else{
                return response()->json('false');
            }
        }else{
            return response()->json('false');
        }

    }
    public function autogenerateInvoiceNoUnique(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $re_year = $request->year;
        $supplier_id = $request->supplier_id;
         $invoice_no  =substr(strtoupper(Auth::user()->vendor->name ), 0, 2) .'-' . rand(0,999999);
        $invoiceUnique = Purchase::where(['supplier_id' => $supplier_id, 'invoice_no' => $invoice_no])->first();
         if ($invoiceUnique)
         {
             $year = date('Y', strtotime($invoiceUnique->date));
             if ($year === $re_year)
             {
                $uni_invoice = $invoice_no. rand(1,10);
                 return response()->json($uni_invoice);
             }else{
                 return response()->json($invoice_no);
             }
         }else{
             return response()->json($invoice_no);
         }

    }



    public function getProductAttributeMapForVendor(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $attribute_id = $request->attribute_id;
        $findAttribute = ProductAttribute::where(['vendor_id' => auth()->user()->vendor_id, 'id' => $attribute_id])->first();
        if ($findAttribute)
        {
           $getAttributeMaps = ProductAttributeMap::where(['vendor_id' => auth()->user()->vendor_id, 'product_attribute_id' => $attribute_id])->get();
           $output = "<option selected>Select</option>";
           foreach ($getAttributeMaps as $getAttributeMap)
           {
               $output .= "<option value='$getAttributeMap->id'>$getAttributeMap->value</option>";
           }
           return $output;
        }else{
            return response()->json('false');
        }
    }



    public function purchsesDiscardStatus($id)
    {
        $purchases_product = PurchaseDetail::findOrFail($id);
        $purchases_product->status = 'DC';
        $purchases_product->save();
        return redirect()->back()->with('success', 'Product discard success!');
    }


    public function submitAdditionalExpenses(Request $request)
    {
        if(!empty($request['store_particular_name'])){
            foreach ($request['store_particular_name'] as $key => $value)
            {
                 PurchaseAdditionalExpenses::create([
                    'purchase_id'       => $request->purchase_id,
                    'vendor_id'        => Auth::user()->vendor_id,
                    'particular'        => $value,
                    'amount'        => $request['store_additional_amount'][$key],
                    'created_by'        => Auth::id(),
                ]);
            }
        }

        return redirect()->back()->with('success', 'Purchase Additional Expenses added successfully!');
    }

    public function updateAdditionalExpenses(Request $request)
    {
        PurchaseAdditionalExpenses::where(['vendor_id' => Auth::user()->vendor_id, 'id' => $request->val_purchase_additional_id])
            ->update([
            'particular' => $request->val_particular_name,
            'amount' => $request->val_particular_amount,
            'updated_by' => Auth::id(),
        ]);
        return response()->json(['success'=> true]);

    }
    public function deleteAdditionalExpenses(Request $request)
    {
        PurchaseAdditionalExpenses::where(['id' => $request->val_purchase_additional_id])
            ->delete();
        return response()->json(['success'=> true]);

    }

    public function getBarcodeList(Request $request, $purchase_detail_id) {
        $purchase_detail = PurchaseDetail::find($purchase_detail_id);
        return view('purchase.get_barcode', compact('purchase_detail'));
    }

    public function purchaseInvoiceList()
    {
        if (request()->ajax()) {
            $query = Purchase::query();
            if (!empty(auth()->user()->warehouse_id)) {
                $query->with(['purchaseProductStock' => function($q){
                    $q->with(['productStockDetails' => function($q){
                        $q->where('warehouse_id', auth()->user()->warehouse_id);
                    }]);
                }]);
            }
            if (!empty(trim(request()->search))) {
                $query->where('invoice_no', 'like', '%' . trim(request()->search) . '%');
            }
            if (!empty(auth()->user()->vendor_id)) {
                $query->where('vendor_id', auth()->user()->vendor_id);
            }
            $query    = $query->get();
            $invoices = [];
            if (!empty(auth()->user()->warehouse_id)) {
                foreach ($query as $q) {
                    if(empty($q->purchaseProductStock)){
                        continue;
                    }
                    if(empty($q->purchaseProductStock->productStockDetails)){
                        continue;
                    }
                    $invoices[trim($q->invoice_no)] = trim($q->invoice_no);
                }
            } else {
                foreach ($query as $q) {
                    $invoices[trim($q->invoice_no)] = trim($q->invoice_no);
                }
            }
            return response()->json($invoices, Response::HTTP_OK);
        }
    }
}
