<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\Http\Requests\Product\ProductStore;
use App\Manufacturer;
use App\ParentProduct;
use App\ParentProductBrand;
use App\ParentProductImage;
use App\Product;
use App\ProductAttribute;
use App\ProductAttributeMap;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductImage;
use App\ProductPool;
use App\ProductPoolPurchaseDetail;
use App\ProductPoolStockDetail;
use App\ProductStock;
use App\Purchase;
use App\PurchaseAttributeDetails;
use App\PurchaseDetail;
use App\StockDetail;
use App\StockedProductBarcode;
use App\Supplier;
use App\SupplierAccount;
use App\SupplierPaymentTransaction;
use App\Tax;
use App\Vendor;
use App\Http\Requests\Warehouse\WarehouseStore;
use App\Http\Requests\Warehouse\WarehouseUpdate;
use App\Warehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class BulkUploadController extends Controller
{

    public function index()
    {
        $title = 'Bulk Uploads';
        $parentCategories = ProductCategory::where('parent_category_id', null)->orWhere('parent_category_id', 0)->active()->get();
        $page_detail = 'Bulk product upload for your Vendor';
        $vendor = ['vendor_id' => auth()->user()->vendor_id];
        $taxes = Tax::where($vendor)->active()->get();
        $categories = ProductCategory::active()->get();
        $brands = ProductBrand::where($vendor)->active()->get();
        $manufactureres = Manufacturer::where($vendor)->active()->get();
        $suppliers = Supplier::where($vendor)->active()->get();
        $warehouses = Warehouse::/*where($vendor)->active()->*/get();
        //$attributes = ProductAttribute::where($vendor)->active()->get();
        //$attribute_maps = ProductAttribute::where($vendor)->get();

        return view('bulk_uploads.index', compact('title','parentCategories','taxes', 'categories', 'brands', 'manufactureres','suppliers','warehouses', 'page_detail'));
    }

    public function importSheet()
    {
        $title = 'Bulk Product Upload';
//        $page_detail = 'Create a Warehouse for your vendor';
        return view('bulk_uploads.sheet', compact('title'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request_data = $request->all();
            if (empty($request_data['row_data'])){
                return response()->json(responseFormat('error', 'Sheet Can not be empty'));
            }

            if (empty($request_data['product_category_id'])){
                return response()->json(responseFormat('error', 'Product Category Can not be Empty'));
            }

            if (empty($request_data['warehouse_id'])){
                return response()->json(responseFormat('error', 'Warehouse Can not be Empty'));
            }

            $request['created_by'] = auth()->id();
            $request['updated_by'] = auth()->id();
            $request['vendor_id'] = auth()->user()->vendor_id;

            $r_d = array();
            foreach ($request_data['row_data'] as $key => $row_data){
                if (empty($row_data['name'])){
                    return response()->json(responseFormat('error', 'Name Can not be Empty at Row '.($key+'1')));
                }
                $r_d[$key]['name'] = trim($row_data['name']);
                $r_d[$key]['slug'] = $row_data['slug']??'';

                $r_d[$key]['product_model'] = trim($row_data['product_model']);
                if (empty($row_data['brand'])){
                    return response()->json(responseFormat('error', 'Brand Can not be Empty at Row '.($key+'1')));
                }
                $r_d[$key]['sku'] = trim($row_data['sku']);
                if (empty($row_data['min_price'])){
                    return response()->json(responseFormat('error', 'Min Price Can not be Empty at Row '.($key+'1')));
                }else if(!floatval($row_data['min_price'])){
                    return response()->json(responseFormat('error', 'Min Price Need Float Value '));
                }
                $r_d[$key]['min_price'] = trim($row_data['min_price']);
                if (empty($row_data['max_price'])){
                    return response()->json(responseFormat('error', 'Max Price Can not be Empty at Row '.($key+'1')));
                }else if(!floatval($row_data['max_price'])){
                    return response()->json(responseFormat('error', 'Max Price Need Float Value '));
                }
                $r_d[$key]['max_price'] = trim($row_data['max_price']);
                $r_d[$key]['tax_id'] = null;
                if (!empty($row_data['tax'])){
                    $tax = Tax::where('percentage',trim($row_data['tax']))->first();
                    if (!empty($tax)) {
                        $r_d[$key]['tax_id'] = $tax->id;
                    }
                }
                $r_d[$key]['product_category_id'] = $request_data['product_category_id'];
//                $r_d[$key]['product_brand_id'] = null;
                if (!empty($row_data['brand'])){
                    $slug = Str::slug(trim($row_data['brand']), '-');
                    $brand = ProductBrand::where('slug',$slug)->where('vendor_id',$request['vendor_id'])->first();
                    if (!empty($brand)) {
                        $r_d[$key]['product_brand_id'] = $brand->id;
                    }else{
                        $slug = Str::slug(trim($row_data['brand']), '-');
                        $parent_brand = ParentProductBrand::where('slug',$slug)
                                        //->where('vendor_id',$request['vendor_id'])
                                        ->first();
                        if (empty($parent_brand)) {

                                    $rd['created_by'] = auth()->user()->id;

                                    $productBrand = ParentProductBrand::create([
                                        'name' => trim($row_data['brand']),
                                        'slug' => $slug,
                                        'website' => '',
                                        'status' => 1,
                                        'created_by' => $rd['created_by'],
                                    ]);
                                    if ($productBrand->id) {
                                        $parent_product_brand_tbl_id = $productBrand->id;
                                    }
                                }
//                            }

                                $slug = Str::slug($row_data['brand'], '-');


                            $rd['created_by'] =  auth()->user()->id;
                            $rd['updated_by'] =  auth()->user()->id;

                            $productBrand = ProductBrand::create([
                                'name' => trim($row_data['brand']),
                                'slug' => $slug,
                                'website' => '',
                                'vendor_id' => $request['vendor_id'],
                                'status' => 1,
                                'is_approved' => 1,
                                'parent_product_brand_tbl_id' => $parent_brand->id  ?? $parent_product_brand_tbl_id,
                                'created_by' => $rd['created_by'],
                                'updated_by' => $rd['updated_by'],
                            ]);

                        $r_d[$key]['product_brand_id'] = $productBrand->id;
//                        }
                    }
                }
                $r_d[$key]['manufacturer_id'] = null;
                if (!empty($row_data['manufacturer'])){
                    $manufacturer = Manufacturer::where('name',trim($row_data['manufacturer']))->where('vendor_id',$request['vendor_id'])->first();
                    if (!empty($manufacturer)) {
                        $r_d[$key]['manufacturer_id'] = $manufacturer->id;
                    }else{
                        $rd['name'] = trim($row_data['manufacturer']);
                        $rd['country_name'] = 'Bangladesh';
                        $rd['status'] = 1;
                        $rd['is_approved'] = 1;
                        $rd['created_by'] = auth()->user()->id;
                        $rd['updated_by'] = auth()->user()->id;
                        $manufacturer = Manufacturer::create($rd);
                        $r_d[$key]['manufacturer_id'] = $manufacturer->id;
                    }
                }
                if (empty($row_data['supplier'])){
                    return response()->json(responseFormat('error', 'Supplier Can not be Empty at Row '.($key+'1')));
                }
                $r_d[$key]['supplier_id'] = null;
                if (!empty($row_data['supplier'])){
                    $supplier = Supplier::where('name',trim($row_data['supplier']))->where('vendor_id',$request['vendor_id'])->first();
                    if (!empty($supplier)) {
                        $r_d[$key]['supplier_id'] = $supplier->id;
                    }else{
                        $rd['name'] = trim($row_data['supplier']);
                        $rd['type'] = 'supplier';
                        $rd['status'] = 1;
                        //$rd['is_approved'] = 1;
                        $rd['created_by'] = auth()->user()->id;
                        $rd['updated_by'] = auth()->user()->id;
                        $supplier = Supplier::create($rd);
                        $r_d[$key]['supplier_id'] = $supplier->id;
                    }
                }
                if (empty($row_data['alert_quantity'])){
                    return response()->json(responseFormat('error', 'Alert Quantity Can not be Empty at Row '.($key+'1')));
                }else if(!intval($row_data['alert_quantity'])){
                    return response()->json(responseFormat('error', 'Alert Quantity Need Integer Value '));
                }
                $r_d[$key]['alert_quantity'] = trim($row_data['alert_quantity']);
                if (empty($row_data['purchase_quantity'])){
                    return response()->json(responseFormat('error', 'Purchase Quantity Can not be Empty at Row '.($key+'1')));
                }else if(!intval($row_data['purchase_quantity'])){
                    return response()->json(responseFormat('error', 'Purchase Quantity Need Integer Value '));
                }
                $r_d[$key]['purchase_quantity'] = trim($row_data['purchase_quantity']);
                if (empty($row_data['purchase_price'])){
                    return response()->json(responseFormat('error', 'Purchase Price Can not be Empty at Row '.($key+'1')));
                }else if(!floatval($row_data['purchase_price'])){
                    return response()->json(responseFormat('error', 'Purchase Price Need Float Value '));
                }
                $r_d[$key]['purchase_price'] = trim($row_data['purchase_price']);
                if (empty($row_data['attribute'])){
                    return response()->json(responseFormat('error', 'Attribue Can not be Empty at Row '.($key+'1')));
                }
                $r_d[$key]['attribute'] = trim($row_data['attribute']);
                $r_d[$key]['product_details'] = trim($row_data['product_details']);
                $r_d[$key]['status'] = trim($row_data['status']);
            }

            $p_s_data =array();
            foreach ($r_d as $index => &$value){
                $p_s_data[$value['supplier_id']][] = $value;
            }

            foreach ($p_s_data as $k => $datas) {
                $purchase_data = array();
                $purchase_data['supplier_id'] = $k;
                $purchase_data['date'] = date('Y-m-d');

                $re_year = $request->year;
                $supplier_id = $purchase_data['supplier_id'];
                $invoice_no = substr(strtoupper(Auth::user()->vendor->name), 0, 2) . '-' . rand(0, 999999);
                $invoiceUnique = Purchase::where(['supplier_id' => $supplier_id, 'invoice_no' => $invoice_no])->first();
                if ($invoiceUnique) {
                    $year = date('Y', strtotime($invoiceUnique->date));
                    if ($year === $re_year) {
                        $uni_invoice = $invoice_no . rand(1, 10);
                        $invoice_no = $uni_invoice;
                    }
                }

                $purchase_data['invoice_no'] = $invoice_no;
                foreach ($datas as $key => &$data) {
                    $is_approved = 1;
                    $PP = ParentProduct::where('name', $data['name'])->first();
                    if (!empty($PP)) {
                        $data['parent_product_id'] = $PP->id;
                    }
                    $parent_product_id = $data['parent_product_id'] ?? 0;
                    // only in new create
                    if (empty($parent_product_id)) {
                        //$allowed_vendor_count = AdminConfig::where('name', 'allowed_vendor_count')->value('value');
                        //if (intval($allowed_vendor_count) == 1) {
                            $parent = $this->_storeParentProduct($data);
                            if ($parent->id) {
                                $is_approved = 1;
                                $parent_product_id = $parent->id;
                            }
                        //}
                    }
                    $product =
                        Product::where('name', $data['name'])->where('vendor_id', $request['vendor_id'])->first();
                    if (!empty($product)) {
                        $data['id'] = $product->id;
                    }
                    if (empty($product)) {
                        $request['status'] = $data['status'] ?? '0';
                        $request['qr_code'] = $data['qr_code'] ?? '';
                        $request['latest_quantity'] = 0;


                        if ($data['slug']) {
                            $slug = Str::slug($data['slug'], '-');
                        } else {
                            $slug = Str::slug($data['name'], '-');
                        }

                        if ($slug) {
                            $slug_name =
                                Product::where(['vendor_id' => Auth::user()->vendor_id, 'slug' => $slug])->value('slug');
                            if ($slug_name) {
                                return response()->json(responseFormat('error', 'Duplicate slug found please insert another slug!'));
                            }
                        }
                        if ($data['name']) {
                            $name =
                                Product::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $data['name']])->value('name');
                            if ($name) {
                                return response()->json(responseFormat('error', 'Duplicate name found please insert another name!'));
                            }
                        }


                        if (!empty($request['store_label_name'])) {
                            $arr = [];
                            foreach ($request['store_label_name'] as $key => $value) {

                                $data['label'] = $request['store_label_name'][$key];
                                $data['value'] = $request['store_label_value'][$key];
                                array_push($arr, $data);

                            }
                            $request['product_specification'] = json_encode($arr);
                        } else {
                            $request['product_specification'] = null;
                        }


                        $product = new Product();
                        $product->name = $data['name'];
                        $product->slug = $slug;
                        $product->product_model = $data['product_model'];
                        $product->sku = $data['sku'];
                        $product->min_price = $data['min_price'];
                        $product->max_price = $data['max_price'];
                        $product->tax_id = $data['tax_id'];
                        $product->product_category_id = $data['product_category_id'];
                        $product->product_brand_id = $data['product_brand_id'];
                        $product->manufacturer_id = $data['manufacturer_id'];
                        $product->alert_quantity = $data['alert_quantity'];
                        $product->product_details = $data['product_details'];
                        $product->status = $data['status'];
                        $product->is_approved = $is_approved ?? 0;
                        $product->parent_product_id = $parent_product_id ?? 0;
                        $product->qr_code = $request['qr_code'];
                        $product->latest_quantity = $request['latest_quantity'];
                        $product->created_by = $request['created_by'];
                        $product->updated_by = $request['updated_by'];
                        $product->vendor_id = $request['vendor_id'];
                        $product->save();
                        $data['id'] = $product->id;
                    }else{
                        return response()->json(responseFormat('error', 'This vendor already has these products.'));
                    }
                    $attributes_data = explode(',', $data['attribute']);
                    $attributes = array();
                    foreach ($attributes_data as $attribute) {
                        $attributes[] = explode(':', $attribute);
                    }
                    $attr_id = '';
                    $attr_map_id = '';
                    foreach ($attributes as $attribute) {
                        $attr =
                            ProductAttribute::where(['name' => ucwords(trim($attribute[0])), 'vendor_id' => $request['vendor_id']])->first();
                        if (empty($attr)) {
                            $attr = ProductAttribute::create([
                                'name' => ucwords(trim($attribute[0])),
                                'status' => 1,
                                'created_by' => $request['created_by'],
                                'updated_by' => $request['updated_by'],
                                'vendor_id' => $request['vendor_id'],
                            ]);
                        }
                        $attr_map =
                            ProductAttributeMap::where(['product_attribute_id' => $attr->id, 'attr_value' => strtolower(trim($attribute[1])), 'vendor_id' => $request['vendor_id']])->first();
                        if (empty($attr_map)) {
                            $attr_map = ProductAttributeMap::create([
                                'product_attribute_id' => $attr->id,
                                'value' => trim($attribute[1]),
                                'attr_value' => strtolower(trim($attribute[1])),
                                'created_by' => $request['created_by'],
                                'updated_by' => $request['updated_by'],
                                'vendor_id' => $request['vendor_id'],
                            ]);
                        }
                        $attr_id .= $attr->id . ',';
                        $attr_map_id .= $attr_map->id . ',';
                    }
                    $attr_id = substr_replace($attr_id, "", -1);
                    $attr_map_id = substr_replace($attr_map_id, "", -1);

                    $purchase_data['store_product_id'][$key] = $data['id'];
                    $purchase_data['store_attribute_id'][$key] = $attr_id;
                    $purchase_data['store_product_attribute_map_id'][$key] = $attr_map_id;
                    $purchase_data['store_quantity'][$key] = $data['purchase_quantity'];
                    $purchase_data['store_price'][$key] = $data['purchase_price'];
                    //$purchase_data['parent_product_id'] = $data['parent_product_id'];
                }
                $purchase = $this->_storePurchase($purchase_data);
                $purchase_details =
                    $this->_storePurchaseDetails($purchase_data, $purchase);
                $this->purchsesPostStatus($purchase);
                $warehouse_id = $request['warehouse_id'];
                $warehouse_detail_id = isset($request['warehouse_section']) ? $request['warehouse_section'] : null;
                //$ProductStockController = new ProductStockController();

                foreach ($purchase_details as $key => $purchase_detail) {
                    $request_data = new Request();
                    $request_data->warehouse_id = $warehouse_id;
                    $request_data->warehouse_detail_id = $warehouse_detail_id;
                    $request_data->quantity = $purchase_data['store_quantity'][$key];
                    $request_data->price = $purchase_data['store_price'][$key];
                    $request_data->total = $request_data->price * $request_data->quantity;
                    $request_data->purchases_id = $purchase->id;
                    $request_data->val_type_generate = null;
                    $request_data->item_id = $purchase_detail['purchase_detail_id'];
                    $request_data->filterBarcode = null;

                    $this->purchasesSubmitToStock($request_data);
                }
            }

            DB::commit();
            return response()->json(responseFormat('success', 'Product Created success'));
        } catch (\Exception $exc) {
            DB::rollback();
            return response()->json(responseFormat('error', $exc->getMessage()));
        }
    }


    public function show (Warehouse $warehouse)
    {
        //
    }

    private function _storeParentProduct($request)
    {

        if(!empty($request['store_label_name'])){
            $arr =[];
            foreach ($request['store_label_name'] as $key => $value)
            {

                $data['label'] = $request['store_label_name'][$key];
                $data['value'] = $request['store_label_value'][$key];
                array_push($arr,$data);

            }
            $request['product_specification'] = json_encode($arr);
        }else{
            $request['product_specification'] = null;
        }

        $productBrandId = ProductBrand::where('id',$request['product_brand_id'])->first()->parent_product_brand_tbl_id;
        $manfId = Manufacturer::where('id',$request['manufacturer_id'])->first()->parent_manufacturer_tbl_id;
        $parent = new ParentProduct();
        $parent->name = $request['name'];
        $parent->slug = $request['slug'];
        $parent->product_model = $request['product_model'];
        $parent->qr_code = $request['qr_code']??null;
        $parent->sku = $request['sku'];
        $parent->product_specification = $request['product_specification']??null;
        $parent->product_details = $request['product_details'];
        $parent->status = 1;
        $parent->is_approved = 1;
        $parent->product_category_id = $request['product_category_id'];
        $parent->product_brand_id = $productBrandId;
        $parent->manufacturer_id = $manfId;
        $parent->product_category_id = $request['product_category_id'];
        $parent->created_by = auth()->id();
        $parent->updated_by = auth()->id();
        $parent->created_at = date('Y-m-d H:i:s');
        $parent->updated_at = date('Y-m-d H:i:s');
        $parent->save();

        return $parent;

    }

    public function update(WarehouseUpdate $request, Warehouse $warehouse)
    {
        //
    }


    public function destroy(Warehouse $warehouse)
    {
        //
    }
    private function uploadImage($file, $name)
    {
        $file_type1 = str_replace("data:image/", "",$file[0]);
        $file_type = str_replace(";base64", "",$file_type1);
        $timestamp = str_replace([' ', ':'], '-', Carbon::now()->toDateTimeString());
        $file_name = $timestamp .'-'.$name. '.' . $file_type;
        $pathToUpload = base_path().'/public/profile/image/'.$file_name;
        $file = base64_decode($file[1]);
        file_put_contents($pathToUpload,$file);
        return $file_name;
    }

    private function unlink($file)
    {
        $pathToUpload = base_path().'/public/profile/image/';
        if ($file != '' && file_exists($pathToUpload. $file)) {
            @unlink($pathToUpload. $file);
        }
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
    private function _storePurchaseDetails($data,$purchase){
        $purchaseDetails = array();
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
            $purchaseDetails[$key]["purchase_detail_id"] = $purchaseDetail->id;
        }
        return $purchaseDetails;
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

    public function purchsesPostStatus(Purchase $purchase)
    {
        $purchase = Purchase::find($purchase->id);
        if (auth()->user()->vendor_id == $purchase->vendor_id) {
            if ($purchase->status === 'draft') {
                $purchase->status = 'posted';
                $purchase->save();
                $this->_storeProductPool($purchase);
            }
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


    public function purchasesSubmitToStock(Request $request)
    {
        $quantity = $request->quantity;
        if ($request->filterBarcode == null ) {
            for ($i=1; $i <= $quantity; $i++ ) {
//                unique_barcode_create:{
//                    $current_timestamp = Carbon::now()->timestamp;
//                    $barcode = 'BC'.$current_timestamp.$i;
                $barcode = 'BC'.randGen(10).$i;
//                    if ($barcode) {
//                        $barcodeUnique = StockedProductBarcode::where('bar_code', $barcode)->first();
//                        if( $barcodeUnique){
//                            goto unique_barcode_create;
//                        }else{
                $barcodes [] = $barcode;
                $barcode_array = $barcodes;
//                        }
//                    }
//                }
            }
        }
        else {
            $barcode_array = $request->filterBarcode;
        }

        $purchase_detail_id = $request->item_id;
        $purchases_id = $request->purchases_id;
        $warehouse_id = $request->warehouse_id;
        $warehouse_detail_id = $request->warehouse_detail_id;
        $vendor_id = Auth::user()->vendor_id;

        $price = $request->price;
        $total = $request->total;
        //end==> get some request value

        //start==> check purchases and vendor_id and purchases_detail
        $check_purchases = Purchase::where(['id' => $purchases_id, 'vendor_id' => $vendor_id])->first();
        $check_purchasesDetail = PurchaseDetail::with(['productPoolDetails'=>function($q){
            $q->with(['productPool']);
        }])->where(['id' => $purchase_detail_id, 'vendor_id' => $vendor_id])->first();
        $unique_purchase_detail_id = $check_purchasesDetail->id;

        if (empty($check_purchases)) {
            return response()->json('not found');
        }
        if (empty($check_purchasesDetail)) {
            return response()->json('not found');
        }
        //end==> check purchases and vendor_id and purchases_detail

        // Start Barcode Checking
        $check_new_barcodes = StockedProductBarcode::select('bar_code')
            ->where([
                ['vendor_id', '=', $vendor_id],
                ['product_id', '=', $check_purchasesDetail->product_id],
                ['purchase_detail_id', '=', $unique_purchase_detail_id],
            ])
            ->whereIn('bar_code', $barcode_array)
            ->get();
        // End of barcode Checking
        if (count($check_new_barcodes) > 0 ) {
            return response()->json(['barcode_exist' => $check_new_barcodes]);
        }


        //start==> at first we need to check status for this purchases product
        if ($check_purchasesDetail->status == 'DC') {
            return response()->json('NotAllowToStock');
        } elseif ($check_purchasesDetail->status == 'FR') {
            return response()->json('NotAllowToStock');
        }
        //end==> at first we need to check status for this purchases product

        //start===> check purchases quantity and request quantity same or not
        if ($check_purchasesDetail->quantity < $quantity) {
            return response()->json('input_quantity_up');
        }
        //end===> check purchases quantity and store quantity same or not


        // just initialize
        $stock_id = 0;
        $check_product_stock_already_exist = ProductStock::where([
            'purchase_id' => $check_purchases->id,
            'vendor_id' => $vendor_id,
        ])->first();


        if ($check_product_stock_already_exist) {
            $stock_id = $check_product_stock_already_exist->id;

            //start===> check quantity up to stock or not
            $stockedQuantity = StockDetail::where([
                    'product_stock_id' => $check_product_stock_already_exist->id,
                    'purchase_detail_id' => $check_purchasesDetail->id,
                    'product_id' => $check_purchasesDetail->product_id,
                ])->sum('quantity') + $quantity;
            if ($check_purchasesDetail->quantity < $stockedQuantity) {
                return response()->json('quantity_up_to_stock');
            }
            // dump($check_purchasesDetail);
            // dump($stockedQuantity);
            //end===> check quantity up to stock or not


            //start==> we need to add every single product depends same condition based on price

            $checkStockConditionProduct = StockDetail::where([
                'product_stock_id' => $check_product_stock_already_exist->id,
                'warehouse_id' => $warehouse_id,
                'warehouse_detail_id' => $warehouse_detail_id,
                'purchase_detail_id' => $check_purchasesDetail->id,
                'product_id' => $check_purchasesDetail->product_id,
                'price' => $price,
            ])->first();

            if ($checkStockConditionProduct) {
                $oldQuantity = $checkStockConditionProduct->quantity;
                $oldTotal = $checkStockConditionProduct->total_price;

                DB::transaction(function () use ($checkStockConditionProduct,$oldQuantity,$oldTotal,$quantity,$total,
                    $vendor_id,$unique_purchase_detail_id,$check_purchasesDetail,$barcode_array, $warehouse_id){

                    $checkStockConditionProduct->update([
                        'quantity' => ($oldQuantity + $quantity),
                        'total_price' => ($oldTotal + $total)
                    ]);
                    $poolDetail = ProductPoolStockDetail::with(['productPool'])->with('stockDetail')->where('stock_detail_id', $checkStockConditionProduct->id)->first();
                    $poolDetail->productPool->stock_quantity = ($poolDetail->productPool->stock_quantity + $quantity);

                    $poolDetail->productPool->updated_by = auth()->user()->id;
                    $poolDetail->productPool->updated_at = Carbon::now();
                    $poolDetail->productPool->save();

                    $poolDetail->stock_quantity = ($poolDetail->stock_quantity + $quantity);
                    $poolDetail->save();

                    if ($poolDetail->available_quantity > 0) {
                        $poolDetail->stock_status = null;
                        $poolDetail->save();

                        $poolDetail->stockDetail->status = null;
                        $poolDetail->stockDetail->save();
                    }

                    if ($poolDetail->productPool->available_quantity > 0) {
                        $poolDetail->productPool->stock_status = null;
                        $poolDetail->productPool->save();

                    }
                    foreach ($barcode_array as $val) {
                        StockedProductBarcode::create([
                            'stock_detail_id' => $checkStockConditionProduct->id,
                            'vendor_id' => $vendor_id,
                            'purchase_detail_id' => $unique_purchase_detail_id,
                            'product_id' => $check_purchasesDetail->product_id,
                            'bar_code' => $val,
                        ]);
                    }


                    // Start Profit ==>
                    $product = Product::where('id',$check_purchasesDetail->product_id)->where('vendor_id',Auth::user()->vendor_id)->first();
                    if (intval($product->average_purchase_price) == 0){
                        $s_d_quantity = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('quantity');
                        $s_d_price = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('total_price');

                        $average_purchase_price = $s_d_price/$s_d_quantity;
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    else{
                        $p_p_ids = ProductPool::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->pluck('id');
                        $p_p_s_ds = ProductPoolStockDetail::whereIn('product_pool_id',$p_p_ids)->get();

                        $available_quantity = 0;
                        foreach($p_p_s_ds as $p_p_s_d){
                            $available_quantity += $p_p_s_d->available_quantity;
                        }

                        $s_d = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->latest()->first();
                        $total_purchase_price_1 = ($available_quantity - $s_d->quantity)*$product->average_purchase_price;
                        $average_purchase_price = ($total_purchase_price_1 + $s_d->total_price) / ($available_quantity);
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    // End Profit <==

                });
                //end==> we need to add every single product depends same condition

            }
            else {
                DB::transaction(function () use ($check_product_stock_already_exist,$vendor_id,$check_purchasesDetail,
                    $warehouse_id,$warehouse_detail_id,$price,$quantity,$total,$barcode_array,$unique_purchase_detail_id){

                    $new_stock_details_id = StockDetail::create([
                        'product_stock_id' => $check_product_stock_already_exist->id,
                        'vendor_id' => $vendor_id,
                        'purchase_detail_id' => $check_purchasesDetail->id,
                        'product_id' => $check_purchasesDetail->product_id,
                        'warehouse_id' => $warehouse_id,
                        'warehouse_detail_id' => $warehouse_detail_id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total_price' => $total,
                    ]);

                    $pool = $check_purchasesDetail->productPoolDetails->productPool;

                    $poolDetail = new ProductPoolStockDetail();
                    $poolDetail->product_pool_id = $pool->id;
                    $poolDetail->stock_quantity = $quantity ;
                    $poolDetail->warehouse_id = $warehouse_id ;
                    $poolDetail->warehouse_detail_id = $warehouse_detail_id;
                    $poolDetail->product_pool_mp_order_confirmation_pending  = $pool->mp_order_confirmation_pending;
                    $poolDetail->stock_detail_id = $new_stock_details_id->id;
                    $poolDetail->save();


                    $pool->stock_quantity = $pool->stock_quantity + $quantity;
                    $pool->updated_by = auth()->user()->id;
                    $pool->updated_at = Carbon::now();
                    $pool->save();

                    if ($pool->available_quantity > 0) {
                        $pool->stock_status = null;
                        $pool->save();
                    }
                    foreach ($barcode_array as $val) {
                        StockedProductBarcode::create([
                            'stock_detail_id' => $new_stock_details_id->id,
                            'vendor_id' => $vendor_id,
                            'purchase_detail_id' => $unique_purchase_detail_id,
                            'product_id' => $check_purchasesDetail->product_id,
                            'bar_code' => $val,
                        ]);
                    }

                    // Start Profit ==>
                    $product = Product::where('id',$check_purchasesDetail->product_id)->where('vendor_id',Auth::user()->vendor_id)->first();
                    if (intval($product->average_purchase_price) == 0){
                        $s_d_quantity = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('quantity');
                        $s_d_price = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('total_price');
                        $average_purchase_price = $s_d_price/$s_d_quantity;
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    else{
                        $p_p_ids = ProductPool::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->pluck('id');
                        $p_p_s_ds = ProductPoolStockDetail::whereIn('product_pool_id',$p_p_ids)->get();
                        $available_quantity = 0;

                        foreach($p_p_s_ds as $p_p_s_d){
                            $available_quantity += $p_p_s_d->available_quantity ;
                        }

                        $s_d = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->latest()->first();
                        $total_purchase_price_1 = ($available_quantity - $s_d->quantity)*$product->average_purchase_price;
                        $average_purchase_price = ($total_purchase_price_1 + $s_d->total_price)/($available_quantity);
                        $product->average_purchase_price = $average_purchase_price;
                        $product->save();
                    }
                    // End Profit <==

                });
            }

            // total quantity
            $getStockedQuantity = StockDetail::where([
                'product_stock_id' => $check_product_stock_already_exist->id,
                'purchase_detail_id' => $check_purchasesDetail->id,
                'product_id' => $check_purchasesDetail->product_id,
            ])->sum('quantity');

        } else {
            // first new create
            DB::transaction(function () use ($purchases_id,$check_purchases,$vendor_id,$check_purchasesDetail,
                $warehouse_id,$warehouse_detail_id,$price,$quantity,$total,$barcode_array,$unique_purchase_detail_id, &$stock_id) {
                $product_stock = ProductStock::create([
                    'purchase_id' => $purchases_id,
                    'vendor_id' => Auth::user()->vendor_id,
                    'supplier_id' => $check_purchases['supplier_id'],
                    'invoice_no' => $check_purchases->invoice_no,
                    'date' => $check_purchases->date,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
                $stock_id = $product_stock->id;
                $last_stock_details_id = StockDetail::create([
                    'product_stock_id' => $product_stock->id,
                    'vendor_id' => $vendor_id,
                    'purchase_detail_id' => $check_purchasesDetail->id,
                    'product_id' => $check_purchasesDetail->product_id,
                    'warehouse_id' => $warehouse_id,
                    'warehouse_detail_id' => $warehouse_detail_id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_price' => $total,
                ]);

                $pool = $check_purchasesDetail->productPoolDetails->productPool;
                $pool->stock_quantity = !empty($pool->stock_quantity) ? $pool->stock_quantity + $quantity : $quantity;

                $pool->updated_by = auth()->user()->id;
                $pool->updated_at = Carbon::now();
                $pool->save();

                if ($pool->available_quantity > 0) {
                    $pool->stock_status = null;
                    $pool->save();
                }

                $poolDetail = new ProductPoolStockDetail();
                $poolDetail->product_pool_id = $pool->id;
                $poolDetail->stock_detail_id = $last_stock_details_id->id;
                $poolDetail->stock_quantity = $quantity;
                $poolDetail->warehouse_id = $warehouse_id;
                $poolDetail->warehouse_detail_id = $warehouse_detail_id;
                $poolDetail->product_pool_mp_order_confirmation_pending  = $pool->mp_order_confirmation_pending;
                $poolDetail->save();

                foreach ($barcode_array as $val) {
                    StockedProductBarcode::create([
                        'stock_detail_id' => $last_stock_details_id->id,
                        'vendor_id' => $vendor_id,
                        'purchase_detail_id' => $unique_purchase_detail_id,
                        'product_id' => $check_purchasesDetail->product_id,
                        'bar_code' => $val,
                    ]);
                }
            });
            $getStockedQuantity = $quantity;
            $check_purchases->status = '';
            $check_purchases->save();


            // Start Profit ==>
            $product = Product::where('id',$check_purchasesDetail->product_id)->where('vendor_id',Auth::user()->vendor_id)->first();

            if (intval($product->average_purchase_price) == 0){
                $s_d_quantity = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('quantity');
                $s_d_price = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->sum('total_price');
                $average_purchase_price = $s_d_price/$s_d_quantity;
                $product->average_purchase_price = $average_purchase_price;
                $product->save();
            }
            else{
                $p_p_ids = ProductPool::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->pluck('id');
                $p_p_s_ds = ProductPoolStockDetail::whereIn('product_pool_id',$p_p_ids)->get();

                $available_quantity = 0;
                foreach($p_p_s_ds as $p_p_s_d){
                    $available_quantity += $p_p_s_d->available_quantity ;
                }

                $s_d = StockDetail::where('product_id',$product->id)->where('vendor_id',Auth::user()->vendor_id)->latest()->first();
                $total_purchase_price_1 = ($available_quantity - $s_d->quantity)*$product->average_purchase_price;
                $average_purchase_price = ($total_purchase_price_1 + $s_d->total_price)/($available_quantity);
                $product->average_purchase_price = $average_purchase_price;
                $product->save();
            }
            // End Profit <==

        }

        //start===> check total quantity of a product based on vendor and insert into products tbl
        $product_latest_quantity = StockDetail::where([
            'product_id' => $check_purchasesDetail->product_id,
        ])->sum('quantity');
        $product_latest_quantity = (int)$product_latest_quantity;
        Product::where([
            'vendor_id' => Auth::user()->vendor_id,
            'id' => $check_purchasesDetail->product_id,
        ])->update(['latest_quantity' => $product_latest_quantity,'updated_by' => Auth::id()]);


        //start===> status change every single purchases product
        if ($getStockedQuantity == $check_purchasesDetail->quantity) {
            $check_purchasesDetail->status = 'FR';
            $check_purchases->status = 'FR';
            $getStatus = 'FR';
        } else {
            $check_purchasesDetail->status = 'PR';
            $check_purchases->status = 'PR';
            $getStatus = 'PR';
        }
        $check_purchases->save();
        $check_purchasesDetail->save();
        //end===> status change every single purchases product
        // now we need to insert barcode for every single stocked product

        //start==> now we are going to payment transaction
        $getProductStock = ProductStock::find($stock_id);
        $getEverySuppliersRows = SupplierPaymentTransaction::where([
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $getProductStock['supplier_id'],
        ])->get();
        $get_total_credit = $getEverySuppliersRows->sum('credit');
        $get_total_debit = $getEverySuppliersRows->sum('debit');

        if ($getEverySuppliersRows->count() > 0) // if existing this vendor info
        {
            $last_record = $getEverySuppliersRows->last(); // get last row info
            if (empty($last_record->debit)) // if the debit is empty for this row
            {
                if ($get_total_credit > $get_total_debit) // if credit >  debit
                {
                    $total_balance = $last_record->balance + $total;
                }else{
                    $total_balance = $total + $last_record->balance;
                }
            }elseif ($last_record->debit)
            {
                if ($get_total_credit > $get_total_debit)
                {
                    $total_balance = ($last_record->balance + $total);
                }else{
                    if ($last_record->balance)
                    {
                        $total_balance = ($total + $last_record->balance);
                    }else{
                        $total_balance = ($total);
                    }
                }
            }
            $total_credit =  $total;
        }else{
            $total_balance = $total;
            $total_credit =  $total;
        }
        SupplierPaymentTransaction::create([
            'product_stock_id' => $getProductStock['id'],
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $getProductStock->supplier_id,
            'purchase_id' => $getProductStock->purchase_id,
            'purchase_invoice_no' => $getProductStock->invoice_no,
            'credit' =>  $total_credit,
            'balance' => $total_balance,
            'transaction_date' => $getProductStock->date,
            'particulars' => 'Transaction  of  Purchases [#'.$getProductStock->invoice_no.']',
            'created_by' => Auth::id(),
            'updated_by'=> Auth::id(),
        ]);

        $getSupplierAccount = SupplierAccount::where([
            'vendor_id' => Auth::user()->vendor_id,
            'supplier_id' => $getProductStock['supplier_id'],
        ])->first();

        if ($getSupplierAccount){
            SupplierAccount::where([
                'vendor_id' => Auth::user()->vendor_id,
                'supplier_id' => $getProductStock['supplier_id'],
            ])->update(['balance' => $total_balance,'updated_by' => Auth::id()]);

        }else{
            SupplierAccount::create([
                'supplier_id' => $getProductStock->supplier_id,
                'vendor_id' => Auth::user()->vendor_id,
                'balance' => $total_balance,
                'created_by' => Auth::id(),
                //'status' => 1, // temporary set
            ]);
        }


        //end==> now we are going to payment transaction

        //return response()->json(['true' => $getStockedQuantity, 'status' => $getStatus]);
    }
}
