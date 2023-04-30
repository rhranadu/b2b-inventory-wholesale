<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;

use App\Http\Requests\Product\ParentProductStore;
use App\Http\Requests\Product\ParentProductUpdate;
use App\Http\Requests\Product\ProductStore;
use App\Http\Requests\Product\ProductUpdate;
use App\ParentManufacturer;
use App\Product;
use App\ParentProduct;
use App\ParentProductBrand;
use App\ParentProductCategory;
use App\ParentProductImage;
use App\ProductCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ParentProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = ParentProduct::with( 'productBrand','productCategory', 'product_images')
            ->get();
        $total_products_list = $products->count();
        $title = 'Parent Products';
        $page_detail = 'List of Total Parent Products - '.$total_products_list;
        return view('super_admin.parent_products.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $products = ParentProduct::with('productBrand','productCategory', 'product_images')
            ->latest()
            ->get();
        $products->total_products_list = count($products);

        return DataTables::of($products)
            ->addIndexColumn()
            ->editColumn('status', function ($product) {
                if ($product->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product->status.'" data_id="'.$product->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product->status.'" data_id="'.$product->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($product) {
                return '<div class="btn-group">
                        <a href="/superadmin/parent_product/' . $product->id . '"  class="btn btn-sm btn-info btn-icon" data-toggle="tooltip" data-placement="auto" title="VIEW" data-original-title="VIEW"><i class="fa fa-eye"></i></a>
                        <a href="/superadmin/parent_product/' . $product->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteProduct('.$product->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteProductForm-'.$product->id.'" action="/superadmin/parent_product/'. $product->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create a Parent Product';
        $page_detail = 'Create a parent product';
        $categories = ProductCategory::get();
        $brands = ParentProductBrand::get();
        $manufacturers = ParentManufacturer::get();

        return view('super_admin.parent_products.create', compact('categories', 'brands', 'manufacturers','title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ParentProductStore $request)
    {
        DB::beginTransaction();
        try {
//            $customMessages = [
//                'required' => 'The :attribute is required.',
//                'integer' => 'The :attribute must be integer.'
//            ];
//            $request->validate([
//                'name' => 'required|string',
//                'product_model' => 'required|string',
//                'product_category_id' => 'required|integer',
//                'product_brand_id' => 'required|integer',
//                'manufacturer_id' => 'required|integer',
//            ],$customMessages);

            $request['status'] = $request->status ?? '0';
            $request['qr_code'] = $request->qr_code ?? null;
            $request['sku'] = $request->qr_code ?? null;
            $request['created_by'] = auth()->id();
            $request['updated_by'] = auth()->id();
            if($request->name) {
                $name = ParentProduct::where(['name' => $request->name])->value('name');
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
            $product = new ParentProduct();
            $product->name = $request->name;
            $product->slug = $request->slug;
            $product->product_model = $request->product_model;
            $product->qr_code = $request->qr_code;
            $product->sku = $request->sku;
            $product->product_specification = $request->product_specification;
            $product->product_details = $request->product_details;
            $product->status = $request->status;
            $product->is_approved = $is_approved ?? 1;
            $product->product_category_id = $request->product_category_id;
            $product->product_brand_id = $request->product_brand_id;
            $product->manufacturer_id = $request->manufacturer_id;
            $product->created_by = $request->created_by;
            $product->updated_by = $request->updated_by;
            $product->save();

            if(!empty($request->image)) {
            // Start of Multiple Images Part
            $original_path = getPathInfo(['products','parent', 'images', 'original', $product->id]);
            $x_600_path = getPathInfo(['products','parent', 'images', 'x_600', $product->id]);
            $x_300_path = getPathInfo(['products','parent', 'images', 'x_300', $product->id]);
            $x_100_path = getPathInfo(['products','parent', 'images', 'x_100', $product->id]);
            $x_50_path = getPathInfo(['products','parent', 'images', 'x_50', $product->id]);

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
                    $setName = $product->slug . '-' . rand() . '-' . uniqid() .  '.' . $extension;

                    if ($height == 1200 && $width == 1200) {
                        $imageLocation = 'backend/uploads/products/parent/images/original/' . $product->id . '/' . $setName;
                        Image::make($img['data'])->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/') . '/' . $imageLocation;
                    }else {
                        $imageLocation = 'backend/uploads/products/parent/images/original/'.$product->id.'/'.$setName;
                        Image::make($img['data'])->resize(1200, 1200)->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                    }

                    unset($imageLocation);
                    $imageLocation = 'backend/uploads/products/parent/images/x_600/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(600, 600)->save($x_600_path.'/'.$setName, 100);
                    $request['x_600_path'] = $imageLocation;
                    $request['x_600_url'] = URL::to('/') . '/'  . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_300/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(300, 300)->save($x_300_path.'/'.$setName, 100);
                    $request['x_300_path'] = $imageLocation;
                    $request['x_300_url'] = URL::to('/') . '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_100/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(100, 100)->save($x_100_path.'/'.$setName, 100);
                    $request['x_100_path'] = $imageLocation;
                    $request['x_100_url'] = URL::to('/') . '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_50/' . $product->id . '/' . $setName;
                    Image::make($img['data'])->resize(50, 50)->save($x_50_path.'/'.$setName, 100);
                    $request['x_50_path'] = $imageLocation;
                    $request['x_50_url'] = URL::to('/') . '/' . $imageLocation;

                    $request['type'] = $extension;
                    $order = ParentProductImage::where('product_id', $product->id)->max('order');
                    if ($order == null || $order == '')
                        $order = 0;
                    $order++;
                    ParentProductImage::create([
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
            return redirect()->action('SuperAdmin\ParentProductController@index')->with('success', 'Product Created success');
        } catch (\Exception $exc) {
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ParentProduct $parentProduct)
    {
        $title = 'View Product';
        $page_detail = 'View a product';
        if ($parentProduct->product_specification){
            $parentProduct->product_specification = json_decode($parentProduct->product_specification,true);
        }
        return view('super_admin.parent_products.view', compact('parentProduct','title', 'page_detail'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ParentProduct $parentProduct)
    {
        $title = 'Edit Parent Product';
        $page_detail = 'Edit a Parent Product';
        $categories = ProductCategory::get();
        $brands = ParentProductBrand::get();
        $manufacturers = ParentManufacturer::get();
        $product_images = ParentProductImage::select('id','x_100_path','original_path_url')->where('product_id', $parentProduct->id)->orderBy('order','ASC')->get();
        $product_images_json = json_decode($product_images);

        return view('super_admin.parent_products.edit', compact('parentProduct',
            'categories', 'brands', 'manufacturers','product_images_json','title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ParentProductUpdate $request, ParentProduct $parentProduct)
    {
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '512M');
        DB::beginTransaction();
        try
        {
            $customMessages = [
                'required' => 'The :attribute is required.',
                'integer' => 'The :attribute must be integer.'
            ];
            $request->validate([
                'name' => 'required|string',
                'product_model' => 'required|string',
                'product_category_id' => 'required|integer',
                'product_brand_id' => 'required|integer',
                'manufacturer_id' => 'required|integer',
            ],$customMessages);
            $request['status'] = $request->status ?? 0;
            $request['qr_code'] = $request->qr_code ?? null;
            $request['sku'] = $request->qr_code ?? null;
            $request['created_by'] = auth()->id();
            $request['updated_by'] = auth()->id();
            if($request->name) {
                $name = ParentProduct::where([
                    'name' => $request->name
                ])->where('id', '<>' , $parentProduct->id)
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

            $parentProduct->update($request->except('_token','image','parenttype','store_label_name','store_label_value'));
            if(!empty($request->image)){

                // delete previous product images
                $productImages = ParentProductImage::where('product_id', $parentProduct->id)->get();
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
                $original_path = getPathInfo(['products','parent', 'images', 'original', $parentProduct->id]);
                $x_600_path = getPathInfo(['products','parent', 'images', 'x_600', $parentProduct->id]);
                $x_300_path = getPathInfo(['products','parent', 'images', 'x_300', $parentProduct->id]);
                $x_100_path = getPathInfo(['products','parent', 'images', 'x_100', $parentProduct->id]);
                $x_50_path = getPathInfo(['products','parent', 'images', 'x_50', $parentProduct->id]);

                makeDirectory($original_path, 0755);
                makeDirectory($x_600_path, 0755);
                makeDirectory($x_300_path, 0755);
                makeDirectory($x_100_path, 0755);
                makeDirectory($x_50_path, 0755);


                foreach ($request->image as $img){
                    $img = json_decode($img,true);
                    $img_type = explode('/', $img['type']);
                    $extension = $img_type[1];
                    $height = Image::make($img['data'])->height();
                    $width = Image::make($img['data'])->width();
                    $setName =  rand() . '-' . uniqid() .  '.' .$extension;

                    if ($height == 1200 && $width == 1200) {
                        $imageLocation = 'backend/uploads/products/parent/images/original/' . $parentProduct->id . '/' . $setName;
                        Image::make($img['data'])->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/') . '/' . $imageLocation;
                    }else {
                        $imageLocation = 'backend/uploads/products/parent/images/original/'.$parentProduct->id.'/'.$setName;
                        Image::make($img['data'])->resize(1200, 1200)->save($original_path.'/'.$setName, 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                    }

                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_600/'.$parentProduct->id.'/'.$setName;
                    Image::make($img['data'])->resize(600, 600)->save($x_600_path.'/'.$setName, 100);
                    $request['x_600_path'] = $imageLocation;
                    $request['x_600_url'] = URL::to('/'). '/'  . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_300/'.$parentProduct->id.'/'.$setName;
                    Image::make($img['data'])->resize(300, 300)->save($x_300_path.'/'.$setName, 100);
                    $request['x_300_path'] = $imageLocation;
                    $request['x_300_url'] = URL::to('/'). '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_100/'.$parentProduct->id.'/'.$setName;
                    Image::make($img['data'])->resize(100, 100)->save($x_100_path.'/'.$setName, 100);
                    $request['x_100_path'] = $imageLocation;
                    $request['x_100_url'] = URL::to('/'). '/' . $imageLocation;
                    unset($imageLocation);

                    $imageLocation = 'backend/uploads/products/parent/images/x_50/'.$parentProduct->id.'/'.$setName;
                    Image::make($img['data'])->resize(50, 50)->save($x_50_path.'/'.$setName, 100);
                    $request['x_50_path'] = $imageLocation;
                    $request['x_50_url'] = URL::to('/') . '/' . $imageLocation;

                    $request['type'] = $extension;
                    $order = ParentProductImage::where('product_id', $parentProduct->id)->max('order');

                    if ($order == null || $order == '')
                        $order = 0;

                    $order++;

                    ParentProductImage::create([
                        'type' => $request->type,
                        'product_id' => $parentProduct->id,
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
            return redirect()->action('SuperAdmin\ParentProductController@index')->with('success', 'Product Updated success');
        }
        catch (\Exception $exc) {
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParentProduct $parentProduct)
    {
        // delete previous product images
        $productImages = ParentProductImage::where('product_id', $parentProduct->id)->get();
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
        $parentProduct->delete();
        return redirect()->back();
    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $success = ParentProduct::where('id', $request->id)
            ->first();
        if ($success)
        {
            $success->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }

    }

    public function getProductSpecificationByAjax(Request $request){
        $product_specifications =  ParentProduct::where([
            'id' => $request->id,
        ])->select('product_specification')->first();
        $product_specifications = json_decode($product_specifications->product_specification,  true);
        return response()->json(['success'=>true,'product_specifications'=>$product_specifications]);
    }
}
