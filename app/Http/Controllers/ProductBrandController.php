<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\ParentProductBrand;
use App\Vendor;
use App\Http\Requests\ProductBrand\ProductBrandStore;
use App\Http\Requests\ProductBrand\ProductBrandUpdate;
use App\ProductBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use DataTables;

class ProductBrandController extends Controller
{
    public function index()
    {
        $title = 'Product Brand';
        $page_detail = 'List of product brand';
        return view('product_brands.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_brands = ProductBrand::where('vendor_id', auth()->user()->vendor_id)->latest()->get();


        return DataTables::of($product_brands)
            ->addIndexColumn()

            ->editColumn('status', function ($product_brand) {
                if ($product_brand->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_brand->status.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_brand->status.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($product_brand) {
                if ($product_brand->image)   return '
                        <div class="pop_img" data-img="'.asset($product_brand->image).'">
                            <img width="50"
                                 src="'.asset($product_brand->image).'"
                                 alt="image">
                        </div>
                ';
                return '
                        <div>
                            No Image
                        </div>
                ';
            })
            ->addColumn('action', function ($product_brand) {
                return '<div class="btn-group">
                        <a href="/admin/product_brand/' . $product_brand->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteBrand('.$product_brand->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteBrandForm-'.$product_brand->id.'" action="/admin/product_brand/'. $product_brand->id .'">
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
        $title = 'Create Product Brand';
        $page_detail = 'Create a product brand for your vendor';
        return view('product_brands.create',compact('title', 'page_detail'));
    }


    public function store(ProductBrandStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {

            $allowed_vendor_count = AdminConfig::where('name','allowed_vendor_count')->value('value');
            if ($allowed_vendor_count == 1) {
                $storeBrand = $this->_storeBrand($request);
                if ($storeBrand->id){
                    $is_approved = 1;
                    $parent_product_brand_tbl_id = $storeBrand->id;
                }
            }

            if($request->slug){
                $slug = Str::slug($request->slug, '-');
            }else{
                $slug = Str::slug($request->name, '-');
            }
            if($slug) {
                $slug_name = ProductBrand::where(['vendor_id' => Auth::user()->vendor_id, 'slug' => $slug])->value('slug');
                if ($slug_name){
                    return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
                }
            }
            if($request->name) {
                $name = ProductBrand::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
                if ($name){
                    return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
                }
            }
            $parent_brand_id = null;
            if ($request->parent_brand_id){
                $parent_brand_id = $request->parent_brand_id;
            }else{
                $checkParentBrand = ParentProductBrand::where('name', $request->name)->value('id');
                if ($checkParentBrand){
                    $parent_brand_id = $checkParentBrand;
                }
            }

            $request['status'] = $request->status ?? 0;
            $request['vendor_id'] = $request->vendor_id ?? auth()->user()->vendor_id;
            $request['created_by'] =  auth()->user()->id;
            $request['updated_by'] =  auth()->user()->id;

            $productBrand = ProductBrand::create([
                                    'name' => $request->name,
                                    'slug' => $slug,
                                    'website' => $request->website,
                                    'vendor_id' => $request->vendor_id,
                                    'status' => $request->status,
                                    'is_approved' => $is_approved ?? 0,
                                    'parent_product_brand_tbl_id' => $parent_product_brand_tbl_id ?? $parent_brand_id,
                                    'created_by' => $request->created_by,
                                    'updated_by' => $request->updated_by,
                                ]);

            $image = $request->file('img');
            if ($image)
            {
                $path_info = getPathInfo(['productBrand', 'vendor', 'images', 'original',$productBrand->id]);
                $thumbnail_path_info = getPathInfo(['productBrand','vendor', 'images', 'thumbnail',$productBrand->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);
                $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();


                $original_imageLocation = 'backend/uploads/productBrand/vendor/images/original/'.$productBrand->id.'/'.$setName;
                Image::make($image)->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;


                // for thumbnail image

                $thumbnail_imageLocation = 'backend/uploads/productBrand/vendor/images/thumbnail/' . $productBrand->id . '/' . $setName;
                Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

                $productBrand->image = $original_imageLocation;
                $productBrand->image_url = $image_url;
                $productBrand->thumbnail_image = $thumbnail_imageLocation;
                $productBrand->thumbnail_image_url = $thumbnail_image_url;
                $productBrand->save();

            }
                // Commit Transaction
                DB::commit();
            return redirect()->action('ProductBrandController@index')->with('success', 'ProductBrand Create Success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }
    private function _storeBrand($request)
    {
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }

        if($slug) {
            $slug_name = ParentProductBrand::where([ 'slug' => $slug])->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ParentProductBrand::where([ 'name' => $request->name])->value('name');
            if ($name){
                return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
            }
        }

        $request['status'] = $request->status ?? 0;
        $request['created_by'] =  auth()->user()->id;

        $productBrand = ParentProductBrand::create([
            'name' => $request->name,
            'slug' => $slug,
            'website' => $request->website,
            'status' => $request->status,
            'created_by' => $request->created_by,
        ]);

        $image = $request->file('img');
        if ($image)
        {
            $path_info = getPathInfo(['productBrand','parent', 'images', 'original',$productBrand->id]);
            $thumbnail_path_info = getPathInfo(['productBrand', 'parent', 'images', 'thumbnail',$productBrand->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);
            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();


            $original_imageLocation = 'backend/uploads/productBrand/parent/images/original/'.$productBrand->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productBrand/parent/images/thumbnail/' . $productBrand->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

            $productBrand->image = $original_imageLocation;
            $productBrand->image_url = $image_url;
            $productBrand->thumbnail_image = $thumbnail_imageLocation;
            $productBrand->thumbnail_image_url = $thumbnail_image_url;
            $productBrand->save();

        }
        return $productBrand;
    }

    public function show (ProductBrand $productBrand)
    {

    }

    public function edit(ProductBrand $productBrand)
    {
        $title = 'Edit Product Brand';
        $page_detail = 'Edit a product brand of your vendor';
        // only allow auth user and admin
        if($productBrand->vendor_id == auth()->user()->vendor_id)
        {
            return view('product_brands.edit', compact('productBrand','title', 'page_detail'));
        }else{
            abort(404);
        }

    }

    public function update(ProductBrandUpdate $request, ProductBrand $productBrand)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->slug){
                $slug = Str::slug($request->slug, '-');
            }else{
                $slug = Str::slug($request->name, '-');
            }
                if($slug) {
                    $slug_name = ProductBrand::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'slug' => $slug
                    ])->where('id', '<>' , $productBrand->id)
                        ->value('slug');
                    if ($slug_name){
                        return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
                    }
                }
                if($request->name) {
                    $name = ProductBrand::where([
                        'vendor_id' => Auth::user()->vendor_id,
                        'name' => $request->name
                    ])->where('id', '<>' , $productBrand->id)
                        ->value('name');
                    if ($name){
                        return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
                    }
                }


                $request['updated_by'] =  auth()->user()->id;
                $request['status'] = $request->status ?? 0;

                $productBrand->update([
                    'name' => $request->name,
                    'slug' => $slug,
                    'website' => $request->website,
                    'status' => $request->status,
                    'updated_by' => $request->updated_by,
                ]);
                $image = $request->file('img');
                if ($image)
                {
                    if ($productBrand->image)
                    {
                        if (file_exists($productBrand->image)){
                            unlink($productBrand->image);
                            unlink($productBrand->thumbnail_image);
                        }
                    }
                    $path_info = getPathInfo(['productBrand', 'images', 'original',$productBrand->id]);
                    $thumbnail_path_info = getPathInfo(['productBrand', 'images', 'thumbnail',$productBrand->id]);
                    makeDirectory($path_info,0755);
                    makeDirectory($thumbnail_path_info,0755);
                    $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                    $original_imageLocation = 'backend/uploads/productBrand/images/original/'.$productBrand->id.'/'.$setName;
                    Image::make($image)->save($path_info.'/'.$setName, 100);
                    $image_url = URL::to('/'). '/' . $original_imageLocation;


                    // for thumbnail image

                    $thumbnail_imageLocation = 'backend/uploads/productBrand/images/thumbnail/' . $productBrand->id . '/' . $setName;
                    Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                    $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

                    $productBrand->image = $original_imageLocation;
                    $productBrand->image_url = $image_url;
                    $productBrand->thumbnail_image = $thumbnail_imageLocation;
                    $productBrand->thumbnail_image_url = $thumbnail_image_url;
                    $productBrand->save();
                }

            // Commit Transaction
            DB::commit();
            return redirect()->action('ProductBrandController@index')->with('success', 'ProductBrand Updated Success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

         return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function destroy(ProductBrand $productBrand)
    {
        // only allow auth user and admin
        if($productBrand->vendor_id == auth()->user()->vendor_id)
        {
            if ($productBrand->image)
            {
                if (file_exists($productBrand->image)){
                    unlink($productBrand->image);
                    unlink($productBrand->thumbnail_image);
                }
            }
            if ($productBrand->brandProducts->count() > 0)
            {
                return redirect()->back()->with('warning', $productBrand->name. ' not allow to delete');
            }
            $productBrand->delete();
            return redirect()->back()->with('success', 'ProductBrand Deleted Success !');
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
        $productBrand = ProductBrand::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
        if ($productBrand)
        {
            $productBrand->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }


    public function getliveSearchByAjax(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $brands = ParentProductBrand::
                where('name', 'like', '%' .$search . '%')
                ->select('name','id')
                ->get();
        }else{
            $brands = ParentProductBrand::
                where('name', 'like', '%' .$search . '%')
                ->select('name','id')
                ->get();
        }
        $response = array();
        foreach($brands as $brand){
            $response[] = array("label"=>$brand->name,"brand_id"=>$brand->id);
        }

        echo json_encode($response);
        exit;
    }

    public function brandDropdownList() {
        if(request()->ajax()){
            $brands = ProductBrand::where('status', 1);
            if(!empty(auth()->user()->vendor_id)) {
                $brands->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty(request()->search)){
                $brands->where('name', 'like', '%'.trim(request()->search).'%');
            }
            $brands = $brands->paginate(10);
            return response()->json($brands, Response::HTTP_OK);
        }
    }


}
