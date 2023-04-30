<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Manufacturer;
use App\ParentProduct;
use App\ParentProductImage;
use App\Product;
use App\ProductBrand;
use App\Vendor;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
class ProductController extends Controller
{

    public function productApproval()
    {
        $vendors = Vendor::active()->get();
        $parentCategories = ProductCategory::where('parent_category_id', null)->orWhere('parent_category_id', 0)->active()->get();
        $products = Product::paginate(10);
        $title = 'Approve Parent Product';
        $page_detail = 'Approve a parent product';
        return view('super_admin.products.product_approval_panel', compact( 'products','parentCategories','vendors','title', 'page_detail'));
    }
    public function parentProductApprove()
    {
        // todo: vendor selection on multivendor
        $parentCategories = ProductCategory::where([['parent_category_id', null]])->active()->get();
        return view('super_admin.products.parent_product_approval_panel', compact( 'parentCategories'));
    }
    public function approveAsNewParent(Request $request)
    {
        
        if (!$request->ajax())
        {
            abort(404);
        }
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $product = Product::where('id', $request->id)->with('product_images')->first();
            $check_brand = ProductBrand::where(['id'=> $product->product_brand_id])->first();
            if ($check_brand->is_approved == 1){
                $brand_id = $check_brand->parent_product_brand_tbl_id;
            }else{
                DB::rollback();
                return response()->json(['success' => false,'msg'=> 'Brand: '. $check_brand->name . ' is not approved yet.']);
            }
            
            $check_manuf = Manufacturer::where(['id'=> $product->manufacturer_id])->first();
            if ($check_manuf->is_approved == 1){
                $manuf_id = $check_manuf->parent_manufacturer_tbl_id;
            }else{
                DB::rollback();
                return response()->json(['success' => false, 'msg'=> 'Manufacturer: '. $check_manuf->name . ' is not approved yet.']);
            }
            if ($product)
            {
                $parent = new ParentProduct();
                $parent->name = $product->name;
                $parent->slug = $product->slug;
                $parent->product_model = $product->product_model;
                $parent->qr_code = $product->qr_code;
                $parent->sku = $product->sku;
                $parent->product_specification = $product->product_specification;
                $parent->product_details = $product->product_details;
                $parent->status = 1;
                $parent->is_approved = 1;
                $parent->product_category_id = $product->product_category_id;
                $parent->product_brand_id = $product->productBrand->parent_product_brand_tbl_id;
                $parent->manufacturer_id = $product->productManufacturer->parent_manufacturer_tbl_id;
                $parent->product_category_id = $product->product_category_id;
                $parent->created_by = auth()->id();
                $parent->updated_by = auth()->id();
                $parent->created_at = date('Y-m-d H:i:s');
                $parent->updated_at = date('Y-m-d H:i:s');
                $parent->save();
                if(!empty($product->product_images)) {
                    // Start of Multiple Images Part
                    $original_path = getPathInfo(['products','parent', 'images', 'original', $parent->id]);
                    $x_600_path = getPathInfo(['products','parent', 'images', 'x_600', $parent->id]);
                    $x_300_path = getPathInfo(['products','parent', 'images', 'x_300', $parent->id]);
                    $x_100_path = getPathInfo(['products','parent', 'images', 'x_100', $parent->id]);
                    $x_50_path = getPathInfo(['products','parent', 'images', 'x_50', $parent->id]);
                    
                    makeDirectory($original_path, 0755);
                    makeDirectory($x_600_path, 0755);
                    makeDirectory($x_300_path, 0755);
                    makeDirectory($x_100_path, 0755);
                    makeDirectory($x_50_path, 0755);
                    
                    $imageData = [];
                    $imageLocation = '';
                    foreach ($product->product_images as $img) {
                        $extension = $img->type;
                        $setName = $parent->slug . '-' . rand() . '-' . uniqid() .  '.' . $extension;
                        if(!empty($img->original_path)){
                            $imageLocation = 'backend/uploads/products/parent/images/original/' . $parent->id . '/' . $setName;
                            File::copy($img->original_path, $imageLocation);
                            $imageData['original_path'] = $imageLocation;
                            $imageData['original_path_url'] = URL::to('/') . '/' . $imageLocation;
                        }
                        if(!empty($img->x_600_path)){
                            $imageLocation = 'backend/uploads/products/parent/images/x_600/' . $parent->id . '/' . $setName;
                            File::copy($img->x_600_path, $imageLocation);
                            $imageData['x_600_path'] = $imageLocation;
                            $imageData['x_600_url'] = URL::to('/') . '/'  . $imageLocation;
                        }
                        if(!empty($img->x_300_path)){
                            $imageLocation = 'backend/uploads/products/parent/images/x_300/' . $parent->id . '/' . $setName;
                            File::copy($img->x_300_path, $imageLocation);
                            $imageData['x_300_path'] = $imageLocation;
                            $imageData['x_300_url'] = URL::to('/') . '/'  . $imageLocation;
                        }
                        if(!empty($img->x_100_path)){
                            $imageLocation = 'backend/uploads/products/parent/images/x_100/' . $parent->id . '/' . $setName;
                            File::copy($img->x_100_path, $imageLocation);
                            $imageData['x_100_path'] = $imageLocation;
                            $imageData['x_100_url'] = URL::to('/') . '/'  . $imageLocation;
                        }
                        if(!empty($img->x_50_path)){
                            $imageLocation = 'backend/uploads/products/parent/images/x_50/' . $parent->id . '/' . $setName;
                            File::copy($img->x_50_path, $imageLocation);
                            $imageData['x_50_path'] = $imageLocation;
                            $imageData['x_50_url'] = URL::to('/') . '/'  . $imageLocation;
                        }
                        $parentProductImages = new ParentProductImage();
                        $parentProductImages->type = $img->type;
                        $parentProductImages->product_id = $parent->id;
                        $parentProductImages->original_path = $imageData['original_path'];
                        $parentProductImages->original_path_url = $imageData['original_path_url'];
                        $parentProductImages->x_600_path =  $imageData['x_600_path'];
                        $parentProductImages->x_600_url =  $imageData['x_600_url'];
                        $parentProductImages->x_300_path =  $imageData['x_300_path'];
                        $parentProductImages->x_300_url =  $imageData['x_300_url'];
                        $parentProductImages->x_100_path =  $imageData['x_100_path'];
                        $parentProductImages->x_100_url =  $imageData['x_100_url'];
                        $parentProductImages->x_50_path =  $imageData['x_50_path'];
                        $parentProductImages->x_50_url =  $imageData['x_50_url'];
                        $parentProductImages->created_by = auth()->user()->id;
                        $parentProductImages->save();
                    }
                }
                if($parent->id){
                    $product->is_approved = 1;
                    $product->parent_product_id = $parent->id;
                    $product->save();
                }
            }else{
                return response()->json(['success' => false]);
            }
        } catch (\Exception $exc) {
            // Rollback Transaction
            DB::rollback();
            return response()->json(['success' => false,'msg'=> 'Something wen wrong', 'raw'=>$exc->getMessage()]);
        }
        DB::commit();
        return response()->json(['success' => true]);
    }
    public function getCategoriesByParent($parent_id = null, $vendor = null)
    {
        $parentVendorCategories = ProductCategory::query();
        if(empty($parent_id)){
            $parentVendorCategories->orWhere('parent_category_id', null);
            $parentVendorCategories->orWhere('parent_category_id', 0);
        }else{
            $parentVendorCategories->orWhere('parent_category_id', $parent_id);
        }
        $parentVendorCategories->active();
        $parentVendorCategories = $parentVendorCategories->get();
        $cat = [];
        foreach($parentVendorCategories as $c){
            $cat[$c->id] = $c->name;
        }
        return response()->json($cat);
    }
    public function getProductListForMap(Request $request)
    {
        $category = 0;
        if ($request->category){
            foreach($request->category as $v){
                if(!empty($v)){
                    $category = $v;
                }
            }
        }
        $searchString = $request->searchString;
        $vendor = $request->vendor;
        $products = Product::with('productBrand','productCategory', 'product_images', 'productVendor', 'parentProduct','productManufacturer')
            ->where('status', 1);
        if(!empty($searchString)){
            $products->where('name' , 'like', '%'.$searchString.'%');
        }
        if(!empty($category)){
            $products->where('product_category_id', $category);
        }
        if(!empty($vendor)){
            $products->where('vendor_id', $vendor);
        }

        if(!empty($request->status_id) ){
            if ($request->status_id == 1){
                $products->whereRaw('COALESCE(parent_product_id, 0) > 0');
                $products->where('is_approved', 1);
            }
            elseif ($request->status_id == 2){
                $products->whereRaw('COALESCE(parent_product_id, 0) > 0');
                $products->where('is_approved', 0);
            }
            elseif ($request->status_id == 3){
                $products->whereRaw('COALESCE(parent_product_id, 0) = 0');
                $products->where('is_approved', 0);
            }
        }
        $products = $products->get();
        return DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('name', function ($products) {
                return '<a href="#" data-toggle="tooltip" '
                .'data-child_product_id = "'.$products->id.'" class="childProductDetails" '
                .'data-placement="auto" title="" data-original-title="">'.$products->name.'</a>';
              
            })
            ->editColumn('parent_name', function ($products) {
                if (!empty($products->parentProduct)) return $products->parentProduct->name ;
                return 'N/A';
            })
            ->editColumn('product_brand', function ($products) {
                if (!empty($products->productBrand)) return $products->productBrand->name ;
                return 'N/A';
            })
            ->editColumn('product_manufacturer', function ($products) {
                if (!empty($products->productManufacturer)) return $products->productManufacturer->name ;
                return 'N/A';
            })
            ->editColumn('product_category', function ($products) {
                if (!empty($products->productCategory)) return $products->productCategory->name ;
                return 'N/A';
            })
            ->editColumn('status', function ($products) {
                if ($products->status == 1) return '<span href="#0"  statusCode="'.$products->status.'" data_id="'.$products->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" statusCode="'.$products->status.'" data_id="'.$products->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('is_approved', function ($products) {
                if ($products->is_approved == 1) return '<span href="#0" statusCode="'.$products->is_approved.'" data_id="'.$products->id.'"   class="badge cursor-pointer badge-success">Approved</span>';
                return '<span href="#0" statusCode="'.$products->is_approved.'" data_id="'.$products->id.'"   class="badge cursor-pointer badge-danger">Disapproved</span>';
            })
            ->addColumn('checkbox', function ($products) {
                return '<div class="text-center form-check custom_checkbox">
                        <input  name="ids[]" class="form-check-input checkbox-product export-approve-product" type="checkbox" id="checkbox-product-'.$products->id.'" data-id="'.$products->id.'" value="'.$products->id.'">
                        <label class="form-check-label" for="checkbox-product-'.$products->id.'"></label>
                    </div>
                        ';
            })
            ->addColumn('action', function ($products) {
                if(!isset($products->parentProduct)){
                    return '<div class="btn-group">'
                    .'<a class="btn btn-warning btn-approve-as-parent" '
                    .'data-id="'.$products->id.'" '
                    .'data-toggle="tooltip" data-placement="auto" title="Use as parent" '
                    .'data-original-title="Use as parent">'
                    .'Use As Parent</a>'
                    .'</div>';
                }     
            })
            ->rawColumns(['status','is_approved','checkbox','action','product_category','product_product','product_brand','parent_name', 'name'])
            ->make(true);
        // return view('super_admin.products.product_list_for_map',compact('products'));
    }

    public function show(Product $product)
    {
        return view('super_admin.products.view', compact('product'));
    }

    public function toggleProductApproval(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $success = Product::where('id', $request->id)
        ->first();
        if ($success)
        {
            $success->update(['is_approved' => $request->setApprovalCode]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }

    public function getChildProducts()
    {

        $childProducts = Product::with('childProductImage','parentProduct','productVendor','productCategory')
            ->paginate(5);
        $vendors = Vendor::select('id','name')
            ->where([
            ['status', 1],
            ])
            ->get();
        $parentCategories = ProductCategory::where([ ['parent_category_id', null]])
            ->active()->get();

        return view('super_admin.products.child_product', compact('childProducts','vendors','parentCategories'));

    }

    public function getChildProductsByFilter(Request $request)
    {
        $childProducts = Product::with('childProductImage','parentProduct','productVendor','productCategory');
        if (!empty($request->vendorId)){
            $childProducts->where('vendor_id', $request->vendorId);
        }
        if (!empty($request->searchString)){
            $childProducts->where('name' , 'like', '%'.$request->searchString.'%');
        }
        $category = 0;
        if(isset($request->category)){
            if(is_array($request->category)){
                foreach($request->category as $v){
                    if(!empty($v)){
                        $category = $v;
                    }
                }
            } else {
                $category = $request->category;
            }
        }
        if(!empty($category)){
            $childProducts->where('product_category_id', $category);
        }
        $childProducts = $childProducts->paginate(20);
        return view('super_admin.products.child_product_by_filter', compact('childProducts'));
    }

    public function getChildProductDetails(Request $request)
    {
        $childProductDetails = Product::with('childProductImage','productBrand','parentProduct','productVendor',
            'productManufacturer','productCategory','productCreatedUser','productUpdatedUser')
            ->where([
            ['id', $request->child_product_id],])
            ->first();
        return view('super_admin.products.child_product_details', compact('childProductDetails'));

    }

    public function getParentProductListForMaping(Request $request)
    {
        $childIds = $request->id;
        $childProducts = Product::select('id','name','product_brand_id','product_category_id','product_id')
            ->whereIn('id', $childIds)
            ->get();
            foreach ($childProducts as  &$childProduct) {
                $check_brand = ProductBrand::where(['id'=> $childProduct->product_brand_id])->first();
                if ($check_brand->is_approved == 1){
                    $brand_id = $check_brand->parent_product_brand_tbl_id;
                }else{
                    return redirect()->back()->with('error! Brand: ', $check_brand->name . ' is not approve yet please approve as parent first for '. $childProduct->name . '!');
                }

                $check_category = ProductCategory::where(['id'=> $childProduct->product_category_id])->first();
                if ($check_category->is_approved == 1){
                    $category_id = $check_category->id;
                }else{
                    return redirect()->back()->with('error', $check_category->name . ' is not approve yet please approve as parent first for '. $childProduct->name . '!');
                }

                $check_product = Manufacturer::where(['id'=> $childProduct->product_id])->first();
                if ($check_category->is_approved == 1){
                    $products_id = $check_category->parent_product_tbl_id;
                }else{
                    return redirect()->back()->with('error', $check_product->name . ' is not approve yet please approve as parent first for '. $childProduct->name . '!');
                }
                unset($childProduct['current_stock']);
                unset($childProduct['stock']);
            }
        $parentProducts = ParentProduct::with('productCategory')
            ->where([
            ['status', 1],
            ['is_approved', 1],
            ])
            ->paginate(20);

        $parentCategories = ProductCategory::where([ ['parent_category_id', null]])
            ->active()->get();
        $title = 'Select Parent Product';
        $page_detail = 'Select a Parent Product';
        return view('super_admin.products.parent_product_list_for_map', compact('childProducts','parentProducts','parentCategories','title', 'page_detail'));
    }
    public function getParentProductsByFilter(Request $request)
    {
        $category = 0;
        if(isset($request->category)){
            if(is_array($request->category)){
                foreach($request->category as $v){
                    if(!empty($v)){
                        $category = $v;
                    }
                }
            } else {
                $category = $request->category;
            }
        }
        $parentProducts = ParentProduct::with('productBrand','productCategory', 'product_images');
        $parentProducts->where('status', 1);
        $parentProducts->where('is_approved', 1);
        if (!empty($request->searchString)){
            $parentProducts->where('name' , 'like', '%'.$request->searchString.'%');
        }
        if(!empty($category)){
            $parentProducts->where('product_category_id', $category);
        }
        $parentProducts = $parentProducts->paginate(20);
        return view('super_admin.products.parent_product_by_filter', compact('parentProducts'));
    }

    public function getFeaturedProducts()
    {

        $featuredProducts = ParentProduct::with('product_images','productCategory')
            ->paginate(10);
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        $parentCategories = ProductCategory::where([ ['parent_category_id', null]])
            ->active()->get();
        $title = 'Featured Products';
        $page_detail = 'List of featured Products';
        return view('super_admin.products.featured_product', compact('featuredProducts','vendors','parentCategories','title', 'page_detail'));

    }

    public function getFeaturedProductsByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $featuredProducts = ParentProduct::with('product_images','productCategory')
            ->get();

        return DataTables::of($featuredProducts)
            ->addIndexColumn()

            ->editColumn('status', function ($featuredProduct) {
                if ($featuredProduct->status == 1) return '<span href="#0"  statusCode="'.$featuredProduct->status.'" data_id="'.$featuredProduct->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" statusCode="'.$featuredProduct->status.'" data_id="'.$featuredProduct->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('is_featured', function ($featuredProduct) {
                if ($featuredProduct->is_featured == 1) return '<span href="#0" statusCode="'.$featuredProduct->is_featured.'" data_id="'.$featuredProduct->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" statusCode="'.$featuredProduct->is_featured.'" data_id="'.$featuredProduct->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('checkbox', function ($featuredProduct) {
                return '<div class="text-center form-check custom_checkbox">
                                        <input  name="ids[]" class="form-check-input export-featured-product" type="checkbox" id="checkbox-featured-product-'.$featuredProduct->id.'" data-id="'.$featuredProduct->id.'" value="'.$featuredProduct->id.'">
                                        <label class="form-check-label" for="checkbox-featured-product-'.$featuredProduct->id.'"></label>
                                    </div>
                        ';
            })
            ->rawColumns(['status','is_featured','checkbox'])
            ->make(true);

    }

    public function getFeaturedProductsMap(Request $request)
    {
        if ($request->featuredInPack ){

            if ($request->val == 'active'){
                foreach ($request->featuredInPack as $id){
                    ParentProduct::where('id', $id)->update([
                        'status' => 1,
                        'is_featured' => 1,
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
            elseif ($request->val == 'deactive'){
                foreach ($request->featuredInPack as $id){
                    ParentProduct::where('id', $id)->update([
                        'is_featured' => 0,
                        'updated_by' => Auth::id(),
                    ]);
                }
            }
        }
        return response()->json(['success' => true]);
    }

    public function getFeaturedProductsByFilter(Request $request)
    {
        $category = 0;
        if(isset($request->category)){
            if(is_array($request->category)){
                foreach($request->category as $v){
                    if(!empty($v)){
                        $category = $v;
                    }
                }
            } else {
                $category = $request->category;
            }
        }
        $parentProducts = ParentProduct::with('productBrand','productCategory', 'product_images');
        $parentProducts->where('status', 1);
        $parentProducts->where('is_approved', 1);
        if (!empty($request->searchString)){
            $parentProducts->where('name' , 'like', '%'.$request->searchString.'%');
        }
        if(!empty($category)){
            $parentProducts->where('product_category_id', $category);
        }
        $featuredProducts = $parentProducts->paginate(10);
        return view('super_admin.products.featured_product_by_filter', compact('featuredProducts'));
    }


    public function productApprovalStatus(Request $request)
    {
        if ($request->productsInPack){
            foreach ($request->productsInPack as $id){
                $product = Product::where([
                    'id' => $id,
                ])->first();
                $product->is_approved = 1;
                $product->updated_by =  Auth::id();
                $product->save();
            }

        }
        return response()->json(['success' => true]);

    }
    public function productDisApprovalStatus(Request $request)
    {
        if ($request->productsInPack){

            foreach ($request->productsInPack as $id){
                $product = Product::where([
                    'id' => $id,
                ])->with('productPurchasesDetails')->first();
                if ($product->productPurchasesDetails->count() > 0 ) {
                    return response()->json(['success' => false,'msg'=>'This Product can not be disapprove, because of purchase dependency!']);
                }else{
                    $product->is_approved = 0;
                    $product->updated_by =  Auth::id();
                    $product->updated_at =  Carbon::now();
                    $product->save();
                }
            }

        }
        return response()->json(['success' => true]);

    }

    public function getParentListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $parentProducts = ParentProduct::where('status', 1)->get();

        return DataTables::of($parentProducts)
            ->addIndexColumn()
            ->editColumn('status', function ($parentProducts) {
                if ($parentProducts->status == 1) return '<span href="#0"  statusCode="'.$parentProducts->status.'" data_id="'.$parentProducts->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0"  statusCode="'.$parentProducts->status.'" data_id="'.$parentProducts->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($parentProducts) {
                return '<div class="btn-group">
                        <a href="#" class="btn btn-sm btn-info btn-icon parentProductSelect" data-parent_product_id="'.$parentProducts->id.'" data-toggle="tooltip" data-placement="auto" title="Select" data-original-title="Select"><i class="fa fa-check"></i></a>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    public function parentProductMap(Request $request)
    {
        if ($request->productsInPack){
            foreach ($request->productsInPack as $productId){
                Product::where('id', $productId)->update([
                    'parent_product_id' => $request->parent_product_id,
                    'is_approved' => 1,
                    'updated_by' => Auth::id(),
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
    public function parentProductUnMap(Request $request)
    {
        if ($request->productsInPack){
            foreach ($request->productsInPack as $productId){
                $product = Product::find($productId);
                $product->parent_product_id = null;
                $product->is_approved = 0;
                $product->updated_by = Auth::id();
                $product->updated_at = Carbon::now();
                $product->save();
            
            }
        }
        return response()->json(['success' => true]);
    }


}
