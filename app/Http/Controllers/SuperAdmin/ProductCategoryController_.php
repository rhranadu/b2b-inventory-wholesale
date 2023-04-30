<?php

namespace App\Http\Controllers\SuperAdmin;

use App\AdminConfig;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategory\ProductCategoryStore;
use App\Http\Requests\ProductCategory\ProductCategoryUpdate;
use App\ParentProduct;
use App\ParentProductCategory;
use App\Product;
use App\Vendor;
use App\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    public function index()
    {
        $title = 'Parent Product Category';
        $page_detail = 'List of parent product category';

        return view('super_admin.categories.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_categories = ParentProductCategory::with('parent')->latest()
            ->get();

        return DataTables::of($product_categories)
            ->addIndexColumn()

            ->editColumn('status', function ($product_category) {
                if ($product_category->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_category->status.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_category->status.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($product_category) {
                return '
                        <div class="pop_img" data-img="'.asset($product_category->image).'">
                            <img width="50"
                                 src="'.asset($product_category->image).'"
                                 alt="image">
                        </div>
                ';
            })
            ->addColumn('action', function ($product_category) {
                return '<div class="btn-group">
                        <a href="/superadmin/product_category/' . $product_category->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteCategory('.$product_category->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteCategoryForm-'.$product_category->id.'" action="/superadmin/product_category/'. $product_category->id .'">
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
        $title = 'Create Parent Product Category';
        $page_detail = 'Create a parent product category';
        $categories  = ParentProductCategory::with('childes')->whereNull('parent_category_id')
            ->where('status', '=', 1)
            ->get();
        return view('super_admin.categories.create', compact( 'title', 'page_detail','categories'));
    }

    public function store(ProductCategoryStore $request)
    {

        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        if($slug) {
            $slug_name = ParentProductCategory::where(['slug' => $slug])->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ParentProductCategory::where(['name' => $request->name])->value('name');
            if ($name){
                return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? 0;

        $parentProductCategory = ParentProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'parent_category_id' => $request->parent_category_id,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => $request->status,
        ]);

        $image = $request->file('img');
        if ($image)
        {
            $path_info = getPathInfo(['productCategory','parent','images', 'original',$parentProductCategory->id]);
            $thumbnail_path_info = getPathInfo(['productCategory','parent', 'images', 'thumbnail',$parentProductCategory->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);

            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
            $original_imageLocation = 'backend/uploads/productCategory/parent/images/original/'.$parentProductCategory->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productCategory/parent/images/thumbnail/' . $parentProductCategory->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;
            $parentProductCategory->image = $original_imageLocation;
            $parentProductCategory->image_url = $image_url;
            $parentProductCategory->thumbnail_image = $thumbnail_imageLocation;
            $parentProductCategory->thumbnail_image_url = $thumbnail_image_url;
            $parentProductCategory->save();

        }

        $allowed_vendor_count = AdminConfig::where('name','allowed_vendor_count')->value('value');
        if ($allowed_vendor_count == 1) {
            $parent_product_category_tbl_id = $parentProductCategory->id;
            $storeCategory= $this->_storeCategory($request,$parent_product_category_tbl_id);

        }
        return redirect()->action('SuperAdmin\ProductCategoryController@index')->with('success', 'Product Category Create Success !');
    }

    private function _storeCategory($request,$parent_product_category_tbl_id)
    {
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        if($slug) {
            $slug_name = ProductCategory::where(['vendor_id' => 0, 'slug' => $slug])->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ProductCategory::where(['vendor_id' => 0, 'name' => $request->name])->value('name');
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
            'parent_product_category_tbl_id' => $parent_product_category_tbl_id ?? 0,
            'vendor_id' => 0,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => $request->status,
        ]);

        $image = $request->file('img');
        if ($image)
        {
            $path_info = getPathInfo(['productCategory', 'vendor', 'images', 'original',$productCategory->id]);
            $thumbnail_path_info = getPathInfo(['productCategory','vendor', 'images', 'thumbnail',$productCategory->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);

            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
            $original_imageLocation = 'backend/uploads/productCategory/vendor/images/original/'.$productCategory->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productCategory/vendor/images/thumbnail/' . $productCategory->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

            $productCategory->image = $original_imageLocation;
            $productCategory->image_url = $image_url;
            $productCategory->thumbnail_image = $thumbnail_imageLocation;
            $productCategory->thumbnail_image_url = $thumbnail_image_url;
            $productCategory->save();

        }

    }

    public function edit($id)
    {
        $title = 'Edit Parent Product Category';
        $page_detail = 'Edit a parent product category';
        $parentProductCategory = ParentProductCategory::where('id',$id)->first();
        $parents_categories  = ParentProductCategory::with(['childes' => function($query) use($parentProductCategory){$query->where('id', '!=', $parentProductCategory->id);}, 'parent'])
            ->whereNull('parent_category_id')
            ->where('status', '=', 1)
            ->where('id', '<>', $parentProductCategory->id)
            ->get();
        return view('super_admin.categories.edit', compact('parentProductCategory','parents_categories','title', 'page_detail'));

    }


    public function update(ProductCategoryUpdate $request,  $id)
    {
        $parentProductCategory = ParentProductCategory::where('id',$id)->first();
        
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        if($slug) {
            $slug_name = ParentProductCategory::where([
                'slug' => $slug
            ])->where('id', '<>' , $id)
                ->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ParentProductCategory::where([
                'name' => $request->name
            ])->where('id', '<>' , $id)
                ->value('name');
            if ($name){
                return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
            }
        }


        $request['status'] = $request->status ?? 0;

        $parentProductCategory->update([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'status' => $request->status,
            'parent_category_id' => $request->parent_category_id,
            'updated_by' => Auth::id(),
        ]);
        $image = $request->file('img');
        if ($image)
        {
            if ($parentProductCategory->image)
            {
                if (file_exists($parentProductCategory->image)){
                    unlink($parentProductCategory->image);
                    unlink($parentProductCategory->thumbnail_image);
                }
            }
            $path_info = getPathInfo(['productCategory','parent','images', 'original',$parentProductCategory->id]);
            $thumbnail_path_info = getPathInfo(['productCategory','parent','images', 'thumbnail',$parentProductCategory->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);
            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
            $original_imageLocation = 'backend/uploads/productCategory/parent/images/original/'.$parentProductCategory->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productCategory/parent/images/thumbnail/' . $parentProductCategory->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

            $parentProductCategory->image = $original_imageLocation;
            $parentProductCategory->image_url = $image_url;
            $parentProductCategory->thumbnail_image = $thumbnail_imageLocation;
            $parentProductCategory->thumbnail_image_url = $thumbnail_image_url;
            $parentProductCategory->save();
        }
        return redirect()->action('SuperAdmin\ProductCategoryController@index')->with('success', 'Product Category Updated Success !');
    }

    public function destroy($id)
    {
        $parentProductCategory = ParentProductCategory::where('id',$id)->first();

        // only allow auth user and admin
            if ($parentProductCategory->image)
            {
                if (file_exists($parentProductCategory->image)){
                    unlink($parentProductCategory->image);
                    unlink($parentProductCategory->thumbnail_image);
                }
            }
            $parentProductCategory->delete();
            return redirect()->back()->with('success', 'ProductCategory Deleted Success !');


    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $productCategory = ParentProductCategory::where('id', $request->id)
            ->first();
        if ($productCategory)
        {
            $productCategory->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }


//Start of  Category Approval Part as parent


    public function productCategoryApproval()
    {
        $parentCategories = ParentProductCategory::where([
            ['status', 1],
        ])->get();
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        $title = 'Approve Product Categories';
        $page_detail = 'Approve a Product Category';
        return view('super_admin.categories.category_approval_panel', compact( 'parentCategories','title', 'page_detail','vendors'));
    }

    public function getChildListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cond = [];
        $where = [];
        if(!empty($request->vendor_id) ){
            $cond = [['vendor_id', $request->vendor_id]];
        }
        if(!empty($request->status_id) ){
            if ($request->status_id == 1){
                $cond = [['parent_product_category_tbl_id', '!=', 0 ],['is_approved', '=', 1 ]];
            }
            elseif ($request->status_id == 2){
                $cond = [['parent_product_category_tbl_id', '!=', 0 ],['is_approved', '=', 0 ]];
            }
            elseif ($request->status_id == 3){
                $cond = [['parent_product_category_tbl_id', '=', 0 ],['is_approved', '=', 0 ]];
            }
        }
        $where = array_merge($where, $cond);
        $product_categories = ProductCategory::with('parent','vendor')
            ->where($where)
            ->get();

        return DataTables::of($product_categories)
            ->addIndexColumn()

            ->editColumn('status', function ($product_category) {
                if ($product_category->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_category->status.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_category->status.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('is_approved', function ($product_category) {
                if ($product_category->is_approved == 1) return '<span href="#0" statusCode="'.$product_category->is_approved.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-success">Approved</span>';
                return '<span href="#0" statusCode="'.$product_category->is_approved.'" data_id="'.$product_category->id.'"   class="badge cursor-pointer badge-danger">Disapproved</span>';
            })
            ->addColumn('checkbox', function ($product_category) {
                return '<div class="text-center form-check custom_checkbox">
                                        <input  name="ids[]" class="form-check-input checkbox-category export-approve-category" type="checkbox" id="checkbox-category-'.$product_category->id.'" data-id="'.$product_category->id.'" value="'.$product_category->id.'">
                                        <label class="form-check-label" for="checkbox-category-'.$product_category->id.'"></label>
                                    </div>
                        ';
            })
            ->rawColumns(['status','is_approved','checkbox'])
            ->make(true);

    }

    public function productCategoryApprovalStatus(Request $request)
    {
        if ($request->categoriesInPack ){

                foreach ($request->categoriesInPack as $id){
                    $category = ProductCategory::where([
                        'id' => $id,
                    ])->first();
                    if ($category->parent_category_id == null){
                        $category->status = 1;
                        $category->is_approved = 1;
                        $category->updated_by =  Auth::id();
                        $category->save();
                    }else{
                        $check_parent_approve =  ProductCategory::where([
                            'id' => $category->parent_category_id,
                            'is_approved' => 1,
                        ])->first();
                        if ($check_parent_approve){
                            $category->is_approved = 1;
                            $category->status = 1;
                            $category->updated_by =  Auth::id();
                            $category->save();
                        }else{
                            return response()->json(['success' => false,'msg'=>'Parent category not approve yet for '.$category->name]);
                        }
                    }
                }

        }
        return response()->json(['success' => true]);

    }
    public function productCategoryDisApprovalStatus(Request $request)
    {
        if ($request->categoriesInPack){

            foreach ($request->categoriesInPack as $id){
                $category = ProductCategory::where([
                    'id' => $id,
                ])->with('categoryProducts')->first();
                if ($category->categoryProducts->count() > 0 ) {
                    return response()->json(['success' => false,'msg'=>'This Category can not be disapprove, because of dependency!']);
                }else{
                    $category->is_approved = 0;
                    $category->status = 0;
                    $category->updated_by =  Auth::id();
                    $category->updated_at =  Carbon::now();
                    $category->save();
                }
            }

        }
        return response()->json(['success' => true]);

    }
    public function approveAsNewParent(Request $request){



        if ($request->categoriesInPack ){
            foreach ($request->categoriesInPack as $id){

                $category = ProductCategory::where([
                    'id' => $id,
                ])->with('parent')->first();

                if(isset($category->parent->slug)){
                    $parent_category_id = ParentProductCategory::where(['slug' => $category->parent->slug])->value('id');
                }else{
                    $parent_category_id = null;
                }

                if($category->slug) {
                    $slug_name = ParentProductCategory::where(['slug' => $category->slug])->value('slug');
                    if ($slug_name){
                        return response()->json(['success' => false,'msg'=>'This Category already exist, please select another one!']);
                    }
                }
                if($category->name) {
                    $name = ParentProductCategory::where(['name' => $category->name])->value('name');
                    if ($name){
                        return response()->json(['success' => false,'msg'=>'This Category already exist, please select another one!']);
                    }
                }

                if($category->parent_category_id){
                    $parentApproveCheck = ProductCategory::where([
                        'id' => $category->parent_category_id,
                        'is_approved' => 1,
                    ])->first();
                    if (!$parentApproveCheck){
                        return response()->json(['success' => false,'msg'=>'Parent category not approve yet for '.$category->name]);
                    }
                }

                if ($category)
                {
                    $parentCategoryFields = [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'status' => $category->status,
                        'parent_category_id' => $parent_category_id,
                        'image' => $category->image,
                        'image_url' => $category->image_url,
                        'thumbnail_image' => $category->thumbnail_image,
                        'thumbnail_image_url' => $category->thumbnail_image_url,
                        'description' => $category->description,
                        'created_by' => auth()->id(),
//                        'updated_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
//                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $parentProductCategory = ParentProductCategory::create($parentCategoryFields);
                    if($parentProductCategory->id){
                        $category->update(['is_approved' => 1,'parent_product_category_tbl_id' => $parentProductCategory->id,
                                            'status' => 1,'updated_by' => auth()->id()]);
                    }
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
        $parentCategories = ParentProductCategory::where([
            ['status', 1],
        ])->get();

        return DataTables::of($parentCategories)
            ->addIndexColumn()
            ->editColumn('status', function ($parentCategory) {
                if ($parentCategory->status == 1) return '<span href="#0"  statusCode="'.$parentCategory->status.'" data_id="'.$parentCategory->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0"  statusCode="'.$parentCategory->status.'" data_id="'.$parentCategory->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($parentCategory) {
                return '<div class="btn-group">
                        <a href="#" class="btn btn-sm btn-info btn-icon parentCategorySelect" data-parent_category_id="'.$parentCategory->id.'" data-toggle="tooltip" data-placement="auto" title="Select" data-original-title="Select"><i class="fa fa-check"></i></a>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }


    public function modalParentCategoryStore(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'slug' => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }


        if($request->slug) {
            $slug_name = ParentProductCategory::where(['slug' => $request->slug])->value('slug');
            if ($slug_name){
                return response()->json(['error' => 'Duplicate slug found please insert another slug!'], 400);
            }
        }
        $request['status'] = $request->status ?? 0;
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        $parentProductCategory = ParentProductCategory::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
            'status' => $request->status,
        ]);

        $image = $request->file('img');
        if ($image)
        {
            $path_info = getPathInfo(['productCategory','parent','images', 'original',$parentProductCategory->id]);
            $thumbnail_path_info = getPathInfo(['productCategory','parent', 'images', 'thumbnail',$parentProductCategory->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);

            $setName = $slug . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
            $original_imageLocation = 'backend/uploads/productCategory/parent/images/original/'.$parentProductCategory->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/productCategory/parent/images/thumbnail/' . $parentProductCategory->id . '/' . $setName;
            Image::make($image)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;
            $parentProductCategory->image = $original_imageLocation;
            $parentProductCategory->image_url = $image_url;
            $parentProductCategory->thumbnail_image = $thumbnail_imageLocation;
            $parentProductCategory->thumbnail_image_url = $thumbnail_image_url;
            $parentProductCategory->save();

        }

        return response()->json('true');
    }

    public function parentCategoryMap(Request $request)
    {
        if ($request->categoryInPack){
            foreach ($request->categoryInPack as $categoryId){
                ProductCategory::where('id', $categoryId)->update([
                    'parent_product_category_tbl_id' => $request->parent_category_id,
                    'is_approved' => 1,
                    'status' => 1,
                    'updated_by' => Auth::id(),
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

    public function parentCategoryUnMap(Request $request)
    {
        if ($request->categoryInPack){
            foreach ($request->categoryInPack as $cateogryId){
                ProductBrand::where('id', $cateogryId)->update([
                    'parent_product_category_tbl_id' => null,
                    'is_approved' => 0,
                    'status' => 0,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        return response()->json(['success' => true]);
    }

}
