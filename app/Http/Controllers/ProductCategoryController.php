<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\ParentProductCategory;
use App\Vendor;
use App\Http\Requests\ProductCategory\ProductCategoryStore;
use App\Http\Requests\ProductCategory\ProductCategoryUpdate;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\DataTables;

class ProductCategoryController extends Controller
{

    public function index()
    {
        $title = 'Product Category';
        $page_detail = 'List of product category';

        return view('product_categories.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_categories = ProductCategory::with('parent')->latest()->get();

        return Datatables::of($product_categories)
            ->addIndexColumn()

            ->editColumn('status', function ($product_category) {
                if ($product_category->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_category->status.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_category->status.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($product_category) {
                if ($product_category->image)  return '
                        <div class="pop_img" data-img="'.asset($product_category->image).'">
                            <img width="50"
                                 src="'.asset($product_category->image).'"
                                 alt="image">
                        </div>
                ';
                return '
                        <div>
                            No Image
                        </div>
                ';
            })
            ->addColumn('action', function ($product_category) {
                return '<div class="btn-group">
                        <a href="/admin/product_category/' . $product_category->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteCategory('.$product_category->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteCategoryForm-'.$product_category->id.'" action="/admin/product_category/'. $product_category->id .'">
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
        $title = 'Create Product Category';
        $page_detail = 'Create a product category';
        $parents_categories  = ProductCategory::with('childes' ,'parent')
            ->where('status', '=', 1)
            ->get();
        return view('product_categories.create', compact( 'parents_categories','title', 'page_detail'));
    }


    public function store(ProductCategoryStore $request)
    {
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        if($slug) {
            $slug_name = ProductCategory::where(['slug' => $slug])->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ProductCategory::where(['name' => $request->name])->value('name');
            if ($name){
                return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
            }
        }
        
        $request['status'] = $request->status ?? 0;
        $request['parent_category_id'] = $request->parent_category_id ?? null;

        $productCategory = ProductCategory::create([
                        'name' => $request->name,
                        'slug' => $slug,
                        'parent_category_id' => $request->parent_category_id,
                        'description' => $request->description,
                        'is_approved' => 1,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                        'status' => $request->status,
                    ]);

        $image = $request->file('img');
        if ($image)
        {
            $path_info = getPathInfo(['productCategory', 'images', 'original',$productCategory->id]);
            $thumbnail_path_info = getPathInfo(['productCategory', 'images', 'thumbnail',$productCategory->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);

            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
            $original_imageLocation = 'backend/uploads/productCategory/images/original/'.$productCategory->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productCategory/images/thumbnail/' . $productCategory->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

            $productCategory->image = $original_imageLocation;
            $productCategory->image_url = $image_url;
            $productCategory->thumbnail_image = $thumbnail_imageLocation;
            $productCategory->thumbnail_image_url = $thumbnail_image_url;
            $productCategory->save();

        }


        return redirect()->action('ProductCategoryController@index')->with('success', 'Product Category Create Success !');
    }

    public function show (ProductCategory $productCategory)
    {

    }

    public function edit(ProductCategory $productCategory)
    {
        $title = 'Edit Product Category';
        $page_detail = 'Edit a product category';
        // only allow auth user and admin
        
        $parents_categories  = ProductCategory::with(['childes' => function($query) use($productCategory){
            $query->where('id', '!=', $productCategory->id);}, 'parent'])
            ->where('status', '=', 1)
            ->where('id', '<>', $productCategory->id)
            ->get();
        return view('product_categories.edit', compact('productCategory','parents_categories','title', 'page_detail'));
        
    }

    public function update(ProductCategoryUpdate $request, ProductCategory $productCategory)
    {
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        if($slug) {
            $slug_name = ProductCategory::where([
                'slug' => $slug
            ])->where('id', '<>' , $productCategory->id)
                ->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ProductCategory::where([
                'name' => $request->name
            ])->where('id', '<>' , $productCategory->id)
                ->value('name');
            if ($name){
                return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
            }
        }


        $request['parent_category_id'] = $request->parent_category_id ?? null;
        $request['status'] = $request->status ?? 0;

        $productCategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'is_approved' => 1,
            'description' => $request->description,
            'parent_category_id' => $request->parent_category_id,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);
        $image = $request->file('img');
        if ($image)
        {
            if ($productCategory->image)
            {
                if (file_exists($productCategory->image)){
                    unlink($productCategory->image);
                    unlink($productCategory->thumbnail_image);
                }
            }
            $path_info = getPathInfo(['productCategory', 'images', 'original',$productCategory->id]);
            $thumbnail_path_info = getPathInfo(['productCategory', 'images', 'thumbnail',$productCategory->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);
            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
            $original_imageLocation = 'backend/uploads/productCategory/images/original/'.$productCategory->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productCategory/images/thumbnail/' . $productCategory->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

            $productCategory->image = $original_imageLocation;
            $productCategory->image_url = $image_url;
            $productCategory->thumbnail_image = $thumbnail_imageLocation;
            $productCategory->thumbnail_image_url = $thumbnail_image_url;
            $productCategory->save();
        }
        return redirect()->action('ProductCategoryController@index')->with('success', 'Product Category Updated Success !');
    }


    public function destroy(ProductCategory $productCategory)
    {
        // only allow auth user and admin
        if ($productCategory->image != null){
            if (file_exists($productCategory->image)) {
                unlink($productCategory->image);
            }
        }
        if ($productCategory->categoryProducts->count() > 0)
        {
            return redirect()->back()->with('warning', $productCategory->name. ' not allow to delete');
        }
        $productCategory->delete();
        return redirect()->back()->with('success', 'ProductCategory Deleted Success !');
    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $productCategory = ProductCategory::where('id', $request->id)->first();
        if ($productCategory)
        {
            $productCategory->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }
    public function getliveSearchByAjax(Request $request)
    {
        $search = $request->search;
        $categories = ProductCategory::where('name', 'like', '%' .$search . '%')
            ->select('name','id')
            ->get();
        $response = array();
        foreach($categories as $category){
            $response[] = array("label"=>$category->name,"category_id"=>$category->id);
        }
        echo json_encode($response);
        exit;
    }
    public function productCategoryDropdownList() {
        if(request()->ajax()){
            $products = ProductCategory::where('status', 1);
            if(!empty(auth()->user()->vendor_id)) {
                $products->where('vendor_id', auth()->user()->vendor_id);
            }
            if(!empty(request()->search)){
                $products->where('name', 'like', '%'.trim(request()->search).'%');
            }
            $products = $products->paginate(10);
            return response()->json($products, Response::HTTP_OK);
        }
    }
}
