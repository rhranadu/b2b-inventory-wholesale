<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\Vendor;
use App\Http\Requests\Product\ProductStore;
use App\Http\Requests\Product\ProductUpdate;
use App\Product;
use App\ParentProduct;
use App\ProductAttribute;
use App\ProductAttributeMap;
use App\ProductBrand;
use App\ProductCategory;
use App\Manufacturer;
use App\ParentProductBrand;
use App\ProductImage;
use App\ParentProductImage;
use App\Tax;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;
class ProductController extends Controller
{

    public function index()
    {
        $products = Product::with('productVendor', 'productBrand','productCategory', 'product_images')
            ->where('vendor_id', auth()->user()->vendor_id)
            ->paginate(10);
        $total_products_list = $products->total();
        $title = 'Products';
        $page_detail = 'List of Total Products - '.$total_products_list;
        return view('products.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $products = Product::with('productVendor', 'productBrand','productCategory', 'product_images')
            ->where('vendor_id', auth()->user()->vendor_id)
            ->latest()
            ->get();
        $products->total_products_list = count($products);

        return DataTables::of($products)
            ->addIndexColumn()

            ->editColumn('pos_discount_price', function ($product) {
                if ($product->pos_discount_price == $product->max_price) return 'N/A';
                else return $product->pos_discount_price;
            })
            ->editColumn('status', function ($product) {
                if ($product->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product->status.'" data_id="'.$product->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product->status.'" data_id="'.$product->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('is_approved', function ($product) {
                if ($product->is_approved == 1) return '<span href="#0" id="ApprovedDisapproved" statusCode="'.$product->is_approved.'" data_id="'.$product->id.'"   class="badge cursor-pointer badge-success">Approved</span>';
                return '<span href="#0" id="ApprovedDisapproved" statusCode="'.$product->is_approved.'" data_id="'.$product->id.'"   class="badge cursor-pointer badge-danger">Disapproved</span>';
            })
            ->addColumn('action', function ($product) {
                return '<div class="btn-group">
                        <a href="/admin/product/' . $product->id . '/show"  class="btn btn-sm btn-info btn-icon" data-toggle="tooltip" data-placement="auto" title="VIEW" data-original-title="VIEW"><i class="fa fa-eye"></i></a>
                        <a href="/admin/product/' . $product->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteProduct('.$product->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteProductForm-'.$product->id.'" action="/admin/product/'. $product->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['pos_discount_price','status','is_approved','action'])
            ->make(true);

    }

    public function parentProductSelection()
    {
        $title = 'Create Product';
        $page_detail = 'Create a product for your Vendor';
        $parentCategories = ProductCategory::where('parent_category_id', null)->orWhere('parent_category_id', 0)->active()->get();
        return view('products.parent_product_selection', compact( 'parentCategories','title', 'page_detail'));
    }
    public function getCategoriesByParent($parent_id = null)
    {
        $parentCategories = ProductCategory::where([['parent_category_id', $parent_id]])->active()->get();
        $cat = [];
        foreach($parentCategories as $c){
            $cat[$c->id] = $c->name;
        }
        return response()->json($cat);
    }
    public function getProductsByAjax(Request $request)
    {
        $category = 0;
        foreach($request->category as $v){
            if(!empty($v)){
                $category = $v;
            }
        }
        $searchString = $request->searchString;
        $vendor = $request->vendor;
        $products = Product::with('productBrand','productCategory', 'product_images', 'productVendor', 'parentProduct')
            ->where('status', 1)
            ->where('is_approved', 1)
            ->where('name' , 'like', '%'.$searchString.'%');
        if(!empty($category)){
            $products->where('product_category_id', $category);
        }
        if(!empty($vendor)){
            $products->where('vendor_id', $vendor);
        }
        if(!empty(Auth::user()->vendor_id)){
            $products->where('vendor_id', Auth::user()->vendor_id);
        }
        $products = $products->paginate(20);
        return view('products.product_list_by_ajax', compact('products'));
    }
    public function getProductsJsonByAjax(Request $request)
    {
        $category = 0;
        if(!empty($request->category)){
            foreach($request->category as $v){
                if(!empty($v)){
                    $category = $v;
                }
            }
        }
        $searchString = $request->searchString;
        $vendor = $request->vendor;
        $products = Product::with('productBrand','productCategory', 'product_images', 'productVendor', 'parentProduct')
            ->where([
                ['status', 1],
                ['name' , 'like', '%'.$searchString.'%']
            ]);
        if(!empty($request->id)){
            $products->whereIn('id', $request->id);
        }
        if(!empty($category)){
            $products->where('product_category_id', $category);
        }
        if(!empty($vendor)){
            $products->where('vendor_id', $vendor);
        }
        $products = $products->get();


        return response()->json($products, Response::HTTP_OK);
    }
    public function getOnlyProductsJsonByAjax(Request $request)
    {
        $searchString = trim($request->search);
        if (!empty($searchString) || $request->has('without_search')) {
            $vendor = $request->vendor;
            $products = Product::select('id','name')->where([
                ['status', 1],
                ['is_approved', 1],
                ['name', 'like', '%' . $searchString . '%']
            ]);

            if (!empty($vendor)) {
                $products->where('vendor_id', $vendor);
            }
            if (!empty(Auth::user()->vendor_id)) {
                $products->where('vendor_id', Auth::user()->vendor_id);
            }
            $products = $products->get();
            $response = array();
            foreach($products as $product){
                $response[$product->id] = $product->name;
            }
            echo json_encode($response);
            exit;
//dd($products);
            //return response()->json($products->all(), Response::HTTP_OK);
        }
    }
    public function create($preCategory = false)
    {
        $title = 'Create Product';
        $page_detail = 'Create a product for your Vendor';
        $vendor = ['vendor_id' => auth()->user()->vendor_id];
        $taxes = Tax::where($vendor)->active()->get();
        $categories = ProductCategory::active()->get();
        $brands = ProductBrand::where($vendor)->active()->get();
        $manufactureres = Manufacturer::where($vendor)->active()->get();
        $attributes = ProductAttribute::where($vendor)->active()->get();
        $attribute_maps = ProductAttribute::where($vendor)->get();

        return view('products.create', compact('taxes', 'categories', 'brands', 'manufactureres', 'attributes', 'attribute_maps', 'preCategory','title', 'page_detail'));
    }


    public function store(ProductStore $request)
    {

        DB::beginTransaction();
        try {
            $parent_product_id = $request->parent_product_id ?? 0;
            // only in new create
            $allowed_vendor_count = AdminConfig::where('name','allowed_vendor_count')->value('value');
            $is_approved = intval($allowed_vendor_count) == 1 || !empty($parent_product_id) ? 1 : 0;
            if(empty($parent_product_id)){
                if (intval($allowed_vendor_count) == 1) {
                    $parent = $this->_storeParentProduct($request);
                    if ($parent->id){
                        $is_approved = 1;
                        $parent_product_id = $parent->id;
                    }
                }
            }
            $request['status'] = $request->status ?? '0';
            $request['qr_code'] = $request->qr_code ?? '';
            $request['latest_quantity'] = 0;
            $request['created_by'] = auth()->id();
            $request['updated_by'] = auth()->id();
            $request['vendor_id'] = auth()->user()->vendor_id;

            if ($request->slug) {
                $slug = Str::slug($request->slug, '-');
            } else {
                $slug = Str::slug($request->name, '-');
            }

            if($slug) {
                $slug_name = Product::where(['vendor_id' => Auth::user()->vendor_id, 'slug' => $slug])->value('slug');
                if ($slug_name){
                    return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
                }
            }
            if($request->name) {
                $name = Product::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
                if ($name){
                    return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
                }
            }


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
            $product = new Product();
            $product->name = $request->name;
            $product->slug = $slug;
            $product->product_model = $request->product_model;
            $product->sku = $request->sku;
            $product->min_price = $request->min_price;
            $product->max_price = $request->max_price;
            $product->tax_id = $request->tax_id;
            $product->product_category_id = $request->product_category_id;
            $product->product_brand_id = $request->product_brand_id;
            $product->manufacturer_id = $request->manufacturer_id;
            $product->alert_quantity = $request->alert_quantity;
            $product->product_details = $request->product_details;
            $product->status = $request->status;
            $product->is_approved = $is_approved ?? 0;
            $product->parent_product_id = $parent_product_id ?? 0;
            $product->qr_code = $request->qr_code;
            $product->latest_quantity = $request->latest_quantity;
            $product->created_by = $request->created_by;
            $product->updated_by = $request->updated_by;
            $product->vendor_id = $request->vendor_id;
            $product->product_specification = $request->product_specification;
            $product->save();
//            $product = Product::create($request->except('_token', 'image'));


            // Start of Multiple Images Part
            $original_path = getPathInfo(['products', 'vendor','images', 'original', $product->id]);
            $x_600_path = getPathInfo(['products', 'vendor','images', 'x_600', $product->id]);
            $x_300_path = getPathInfo(['products', 'vendor','images', 'x_300', $product->id]);
            $x_100_path = getPathInfo(['products', 'vendor','images', 'x_100', $product->id]);
            $x_50_path = getPathInfo(['products', 'vendor','images', 'x_50', $product->id]);

            makeDirectory($original_path, 0755);
            makeDirectory($x_600_path, 0755);
            makeDirectory($x_300_path, 0755);
            makeDirectory($x_100_path, 0755);
            makeDirectory($x_50_path, 0755);

            if(!empty($request->image)) {
                foreach ($request->image as $img) {
                    $img = json_decode($img, true);
                    $img_type = explode('/', $img['type']);
                    $extension = $img_type[1];
                    $height = Image::make($img['data'])->height();
                    $width = Image::make($img['data'])->width();
                    $setName = $product->slug . '-' . rand() . '-' . uniqid() .  '.' . $extension;

                    if ($height == 1200 && $width == 1200) {
                        $imageLocation = 'backend/uploads/products/vendor/images/original/' . $product->id . '/' . $setName;
                        Image::make($img['data'])->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/') . '/' . $imageLocation;
                    }else {
                        $imageLocation = 'backend/uploads/products/vendor/images/original/'.$product->id.'/'.$setName;
                        Image::make($img['data'])->resize(1200, 1200)->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                    }

                    unset($imageLocation);
                    $imageLocation = 'backend/uploads/products/vendor/images/x_600/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(600, 600)->save($x_600_path.'/'.$setName, 100);
                    $request['x_600_path'] = $imageLocation;
                    $request['x_600_url'] = URL::to('/') . '/'  . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/vendor/images/x_300/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(300, 300)->save($x_300_path.'/'.$setName, 100);
                    $request['x_300_path'] = $imageLocation;
                    $request['x_300_url'] = URL::to('/') . '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/vendor/images/x_100/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(100, 100)->save($x_100_path.'/'.$setName, 100);
                    $request['x_100_path'] = $imageLocation;
                    $request['x_100_url'] = URL::to('/') . '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/vendor/images/x_50/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(50, 50)->save($x_50_path.'/'.$setName, 100);
                    $request['x_50_path'] = $imageLocation;
                    $request['x_50_url'] = URL::to('/') . '/' . $imageLocation;

                    $request['type'] = $extension;
                    $order = ProductImage::where('product_id', $product->id)->max('order');
                    if ($order == null || $order == '')
                        $order = 0;
                    $order++;
                    ProductImage::create([
                        'type' => $request->type,
                        'product_id' => $product->id,
                        'order' => $order,
                        'original_path' => $request->original_path,
                        'original_path_url' => $request->original_path_url,
                        'x_600_path' => $request->x_600_path,
                        'x_600_url' => $request->x_600_url,
                        'x_300_path' => $request->x_300_path,
                        'x_300_url' => $request->x_300_url,
                        'x_100_path' => $request->x_100_path,
                        'x_100_url' => $request->x_100_url,
                        'x_50_path' => $request->x_50_path,
                        'x_50_url' => $request->x_50_url,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
            DB::commit();
            return redirect()->action('ProductController@index')->with('success', 'Product Created success');
        } catch (\Exception $exc) {
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }
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
        $productBrandId = ProductBrand::where('id',$request->product_brand_id)->first()->parent_product_brand_tbl_id;
        $manfId = Manufacturer::where('id',$request->manufacturer_id)->first()->parent_manufacturer_tbl_id;
        $parent = new ParentProduct();
        $parent->name = $request->name;
        $parent->slug = $request->slug;
        $parent->product_model = $request->product_model;
        $parent->qr_code = $request->qr_code;
        $parent->sku = $request->sku;
        $parent->product_specification = $request->product_specification;
        $parent->product_details = $request->product_details;
        $parent->status = 1;
        $parent->is_approved = 1;
        $parent->product_category_id = $request->product_category_id;
        $parent->product_brand_id = $productBrandId;
        $parent->manufacturer_id = $manfId;
        $parent->product_category_id = $request->product_category_id;
        $parent->created_by = auth()->id();
        $parent->updated_by = auth()->id();
        $parent->created_at = date('Y-m-d H:i:s');
        $parent->updated_at = date('Y-m-d H:i:s');
        $parent->save();

        if(!empty($request->image)) {
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


            foreach ($request->image as $img) {
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $height = Image::make($img['data'])->height();
                $width = Image::make($img['data'])->width();
                $setName = $parent->slug . '-' . rand() . '-' . uniqid() .  '.' . $extension;

                if ($height == 1200 && $width == 1200) {
                    $imageLocation = 'backend/uploads/products/parent/images/original/' . $parent->id . '/' . $setName;
                    Image::make($img['data'])->save($original_path.'/'.$setName, 100);
                    $request['original_path'] = $imageLocation;
                    $request['original_path_url'] = URL::to('/') . '/' . $imageLocation;
                }else {
                    $imageLocation = 'backend/uploads/products/parent/images/original/'.$parent->id.'/'.$setName;
                    Image::make($img['data'])->resize(1200, 1200)->save($original_path.'/'.$setName, 100);
                    $request['original_path'] = $imageLocation;
                    $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                }

                unset($imageLocation);
                $imageLocation = 'backend/uploads/products/parent/images/x_600/' . $parent->id . '/' . $setName;
                Image::make($img['data'])->resize(600, 600)->save($x_600_path.'/'.$setName, 100);
                $request['x_600_path'] = $imageLocation;
                $request['x_600_url'] = URL::to('/') . '/'  . $imageLocation;
                unset($imageLocation);

                $imageLocation = 'backend/uploads/products/parent/images/x_300/' . $parent->id . '/' . $setName;
                Image::make($img['data'])->resize(300, 300)->save($x_300_path.'/'.$setName, 100);
                $request['x_300_path'] = $imageLocation;
                $request['x_300_url'] = URL::to('/') . '/' . $imageLocation;
                unset($imageLocation);

                $imageLocation = 'backend/uploads/products/parent/images/x_100/' . $parent->id . '/' . $setName;
                Image::make($img['data'])->resize(100, 100)->save($x_100_path.'/'.$setName, 100);
                $request['x_100_path'] = $imageLocation;
                $request['x_100_url'] = URL::to('/') . '/' . $imageLocation;
                unset($imageLocation);

                $imageLocation = 'backend/uploads/products/parent/images/x_50/' . $parent->id . '/' . $setName;
                Image::make($img['data'])->resize(50, 50)->save($x_50_path.'/'.$setName, 100);
                $request['x_50_path'] = $imageLocation;
                $request['x_50_url'] = URL::to('/') . '/' . $imageLocation;

                $request['type'] = $extension;
                $order = ParentProductImage::where('product_id', $parent->id)->max('order');
                if ($order == null || $order == '')
                    $order = 0;
                $order++;
                ParentProductImage::create([
                    'type' => $request->type,
                    'product_id' => $parent->id,
                    'order' => $order,
                    'original_path' => $request->original_path,
                    'original_path_url' => $request->original_path_url,
                    'x_600_path' => $request->x_600_path,
                    'x_600_url' => $request->x_600_url,
                    'x_300_path' => $request->x_300_path,
                    'x_300_url' => $request->x_300_url,
                    'x_100_path' => $request->x_100_path,
                    'x_100_url' => $request->x_100_url,
                    'x_50_path' => $request->x_50_path,
                    'x_50_url' => $request->x_50_url,
                    'created_by' => auth()->id(),
                ]);
            }
        }
        return $parent;

    }

    public function show(Product $product, $useExisting = false)
    {
        $title = 'View Product';
        $page_detail = 'View a product of your Vendor';
        if(!$useExisting && auth()->user()->vendor_id != $product->vendor_id){
            abort(404);
        }
        if ($product->product_specification){
            $product->product_specification = json_decode($product->product_specification,true);
        }
        return view('products.view', compact('product','title', 'page_detail'));
    }


    public function edit(Product $product,  $useExisting = false)
    {
        $title = 'Edit Product';
        $page_detail = 'Edit a product of your Vendor';
        //specific vendor can use other vendor's product to create own
        if(!$useExisting && auth()->user()->vendor_id != $product->vendor_id){
            abort(404);
        }
        $vendor = ['vendor_id' => auth()->user()->vendor_id];
        $taxes = Tax::where($vendor)->active()->get();
        $categories = ProductCategory::active()->get();
        $brands = ProductBrand::where($vendor)->active()->get();
        $manufactureres = Manufacturer::where($vendor)->active()->get();
        $attributes = ProductAttribute::where($vendor)->active()->get();
        $attribute_maps = ProductAttributeMap::where($vendor)->get();
        $product_images = ProductImage::select('id','x_100_path','original_path_url','x_600_path','x_300_path','x_50_path')->where('product_id', $product->id)->orderBy('order','ASC')->get();
        $product_images_json = json_decode($product_images);
        if($useExisting){
            $title = 'Create Product';
            $page_detail = 'Create a product for your Vendor';
            return view('products.use_existing_to_create', compact('product', 'taxes',
                'categories', 'brands', 'manufactureres','attributes', 'attribute_maps','title', 'page_detail'));
        }
        return view('products.edit', compact('product', 'taxes',
            'categories', 'brands', 'manufactureres','attributes', 'attribute_maps','product_images_json','title', 'page_detail'));
    }


    public function update(ProductUpdate $request, Product $product)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');

        DB::beginTransaction();
        try
        {
            $request['status'] = $request->status ?? 0;
            $request['updated_by'] = auth()->id();
            $request['vendor_id'] = auth()->user()->vendor_id;
            if($request->slug){
                $request['slug'] = Str::slug($request->slug, '-');
            }else{
                $request['slug'] = Str::slug($request->name, '-');
            }
            if($request->slug) {
                $slug_name = Product::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'slug' => $request->slug
                ])->where('id', '<>' , $product->id)
                    ->value('slug');
                if ($slug_name){
                    return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
                }
            }
            if($request->name) {
                $name = Product::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'name' => $request->name
                ])->where('id', '<>' , $product->id)
                    ->value('name');
                if ($name){
                    return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
                }
            }
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
            $product->update($request->except('_token','image','parenttype','store_label_name','store_label_value'));

            // Start of Multiple Images Part
            // delete previous product images
            $productImages = ProductImage::where('product_id', $product->id)->get();
            if (count($productImages) > 0){
                foreach ($productImages as $productImg){
                    if ($productImg->original_path)
                    {
                        if (file_exists($productImg->original_path)){
                            unlink($productImg->original_path);
                            unlink($productImg->x_600_path);
                            unlink($productImg->x_300_path);
                            unlink($productImg->x_100_path);
                            unlink($productImg->x_50_path);
                        }
                    }
                    $productImg->delete();
                }
            }
            // End of delete product images
            $original_path = getPathInfo(['products', 'images', 'original', $product->id]);
            $x_600_path = getPathInfo(['products', 'images', 'x_600', $product->id]);
            $x_300_path = getPathInfo(['products', 'images', 'x_300', $product->id]);
            $x_100_path = getPathInfo(['products', 'images', 'x_100', $product->id]);
            $x_50_path = getPathInfo(['products', 'images', 'x_50', $product->id]);

            makeDirectory($original_path, 0755);
            makeDirectory($x_600_path, 0755);
            makeDirectory($x_300_path, 0755);
            makeDirectory($x_100_path, 0755);
            makeDirectory($x_50_path, 0755);

            if(!empty($request->image)){
                foreach ($request->image as $img){
                    $img = json_decode($img,true);
                    $img_type = explode('/', $img['type']);
                    $extension = $img_type[1];
                    $height = Image::make($img['data'])->height();
                    $width = Image::make($img['data'])->width();
                    $setName = $product->slug . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                    if ($height == 1200 && $width == 1200) {
                        $imageLocation = 'backend/uploads/products/images/original/' . $product->id . '/' . $setName;
                        Image::make($img['data'])->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/') . '/' . $imageLocation;
                    }else {
                        $imageLocation = 'backend/uploads/products/images/original/'.$product->id.'/'.$setName;
                        Image::make($img['data'])->resize(1200, 1200)->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                    }

                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/images/x_600/'.$product->id.'/'.$setName;
                    Image::make($img['data'])->resize(600, 600)->save($x_600_path.'/'.$setName, 100);
                    $request['x_600_path'] = $imageLocation;
                    $request['x_600_url'] = URL::to('/'). '/'  . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/images/x_300/'.$product->id.'/'.$setName;
                    Image::make($img['data'])->resize(300, 300)->save($x_300_path.'/'.$setName, 100);
                    $request['x_300_path'] = $imageLocation;
                    $request['x_300_url'] = URL::to('/'). '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/images/x_100/'.$product->id.'/'.$setName;
                    Image::make($img['data'])->resize(100, 100)->save($x_100_path.'/'.$setName, 100);
                    $request['x_100_path'] = $imageLocation;
                    $request['x_100_url'] = URL::to('/'). '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/images/x_50/'.$product->id.'/'.$setName;
                    Image::make($img['data'])->resize(50, 50)->save($x_50_path.'/'.$setName, 100);
                    $request['x_50_path'] = $imageLocation;
                    $request['x_50_url'] = URL::to('/') . '/' . $imageLocation;

                    $request['type'] = $extension;
                    $order = ProductImage::where('product_id', $product->id)->max('order');

                    if ($order == null || $order == '')
                        $order = 0;

                    $order++;

                    ProductImage::create([
                        'type' => $request->type,
                        'product_id' => $product->id,
                        'order' => $order,
                        'original_path' => $request->original_path,
                        'original_path_url' => $request->original_path_url,
                        'x_600_path' => $request->x_600_path,
                        'x_600_url' => $request->x_600_url,
                        'x_300_path' => $request->x_300_path,
                        'x_300_url' => $request->x_300_url,
                        'x_100_path' => $request->x_100_path,
                        'x_100_url' => $request->x_100_url,
                        'x_50_path' => $request->x_50_path,
                        'x_50_url' => $request->x_50_url,
                        'updated_by' => auth()->id(),
                    ]);

                }
            }

            DB::commit();
            return redirect()->action('ProductController@index')->with('success', 'Product Updated success');
        }
        catch (\Exception $exc) {
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }

    }


    public function destroy(Product $product)
    {

        if (auth()->user()->vendor_id == $product->vendor_id)
        {


            if ($product->productPurchasesDetails->count() > 0 || $product->productStockes->count() > 0 || $product->stock->count() > 0  )
            {
                return redirect()->back()->with('warning', $product->name. ' not allow to delete');
            }else{
                // delete previous product images
                $productImages = ProductImage::where('product_id', $product->id)->get();
                if (count($productImages) > 0){
                    foreach ($productImages as $productImg){
                        if ($productImg->original_path)
                        {
                            if (file_exists($productImg->original_path)){
                                unlink($productImg->original_path);
                                unlink($productImg->x_600_path);
                                unlink($productImg->x_300_path);
                                unlink($productImg->x_100_path);
                                unlink($productImg->x_50_path);
                            }
                        }
                        $productImg->delete();
                    }
                }
                // End of delete product images
                $product->delete();
            }

            return redirect()->back();
        }else{
            abort(404);
        }

    }

    public function activeDeactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $success = Product::where('id', $request->id)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->first();
        if ($success)
        {
            $success->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }

    }

    public function getProductComponenetForVendor(Request $request)
    {

        if (!$request->ajax())
        {
            abort(404);
        }

        if ($request->product_attribute_id)
        {
            $ProductAttribute_maps = ProductAttributeMap::where('product_attribute_id', $request->product_attribute_id)->get();
            if ($ProductAttribute_maps)
            {
                $ProductAttributeMapOutput = '<option>Please Select Attribute Map</option>';
                foreach ($ProductAttribute_maps as $ProductAttribute_map)
                {
                    $ProductAttributeMapOutput .= '<option value="'.$ProductAttribute_map->id.'">'.$ProductAttribute_map->value.'</option>';
                }
            }
            return response()->json([
                'pattribute_maps' => $ProductAttributeMapOutput,
            ]);
        }


        $vendor = ['vendor_id' => $request->vendor_id];
        $taxes = Tax::where($vendor)->get();
        $productCategories = ProductCategory::get();
        $productBrands = ProductBrand::where($vendor)->get();
        $ProductAttributes = ProductAttribute::where($vendor)->get();
        $ProductAttribute_maps = ProductAttributeMap::where($vendor)->get();
        $Manufacturers = Manufacturer::where($vendor)->get();
        if ($taxes)
        {
            $taxOutput = '<option>Please Select Tax</option>';
            foreach ($taxes as $tax)
            {
                $taxOutput .= '<option id="tax'.$tax->id.'" value="'.$tax->id.'">'.$tax->percentage.'</option>';
            }
        }

        if ($productCategories)
        {
            $ProductCategoryOutput = '<option>Please Select Category</option>';
            foreach ($productCategories as $ProductCategory)
            {
                $ProductCategoryOutput .= '<option value="'.$ProductCategory->id.'">'.$ProductCategory->name.'</option>';
            }
        }

        if ($productBrands)
        {
            $productBrandOutput = '<option>Please Select Brand</option>';
            foreach ($productBrands as $productBrand)
            {
                $productBrandOutput .= '<option value="'.$productBrand->id.'">'.$productBrand->name.'</option>';
            }
        }

        if ($Manufacturers)
        {
            $ManufacturerOutput ='<option>Please Select Manufacturer</option>';
            foreach ($Manufacturers as $Manufacturer)
            {
                $ManufacturerOutput .= '<option value="'.$Manufacturer->id.'">'.$Manufacturer->name.'</option>';
            }
        }

        if ($ProductAttributes)
        {
            $ProductAttributeOutput = '<option>Please Select Attribute</option>';
            foreach ($ProductAttributes as $ProductAttribute)
            {
                $ProductAttributeOutput .= '<option value="'.$ProductAttribute->id.'">'.$ProductAttribute->name.'</option>';
            }
        }
        if ($ProductAttribute_maps)
        {
            $ProductAttributeMapOutput = '<option>Please Select Attribute Map</option>';
            foreach ($ProductAttribute_maps as $ProductAttribute_map)
            {
                $ProductAttributeMapOutput .= '<option value="'.$ProductAttribute_map->id.'">'.$ProductAttribute_map->value.'</option>';
            }
        }
        return response()->json([
            'tax'       => $taxOutput,
            'category'  => $ProductCategoryOutput,
            'brand'     => $productBrandOutput,
            'pattribute' => $ProductAttributeOutput,
            'pattribute_maps' => $ProductAttributeMapOutput,
            'manufacturer' => $ManufacturerOutput,
        ]);
    }

    public function getProductSpecificationByAjax(Request $request){
        $product_specifications =  Product::where([
            'id' => $request->id,
        ])->select('product_specification')->first();
        $product_specifications = json_decode($product_specifications->product_specification,  true);
        return response()->json(['success'=>true,'product_specifications'=>$product_specifications]);
    }

    public function productDropdownList() {
        if(request()->ajax()){
            $products = Product::where('status', 1);
            if(!empty(auth()->user()->vendor_id)) {
                $products->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty(request()->brand_id)) {
                $products->where('product_brand_id', request()->brand_id);
            }
            if(!empty(request()->category_id)) {
                $products->where('product_category_id', request()->category_id);
            }
            if(!empty(request()->search)){
                $products->where('name', 'like', '%'.trim(request()->search).'%');
            }
            $products = $products->paginate(10);
            return response()->json($products, Response::HTTP_OK);
        }
    }
}
