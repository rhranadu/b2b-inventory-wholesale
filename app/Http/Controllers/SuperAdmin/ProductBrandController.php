<?php

namespace App\Http\Controllers\SuperAdmin;
use App\AdminConfig;
use App\Http\Controllers\Controller;
use App\ProductBrand;
use App\Vendor;
use App\Http\Requests\ProductBrand\ProductBrandStore;
use App\Http\Requests\ProductBrand\ProductBrandUpdate;
use App\ParentProductBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class ProductBrandController extends Controller
{
    public function index()
    {
        $title = 'Product Brand';
        $page_detail = 'List of product brands';
        return view('super_admin.brands.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_brands = ParentProductBrand::latest()->get();


        return DataTables::of($product_brands)
            ->addIndexColumn()

            ->editColumn('status', function ($product_brand) {
                if ($product_brand->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_brand->status.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_brand->status.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($product_brand) {
                return '
                        <div class="pop_img" data-img="'.asset($product_brand->image).'">
                            <img width="50"
                                 src="'.asset($product_brand->image).'"
                                 alt="image">
                        </div>
                ';
            })
            ->addColumn('action', function ($product_brand) {
                return '<div class="btn-group">
                        <a href="/superadmin/product_brand/' . $product_brand->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteBrand('.$product_brand->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteBrandForm-'.$product_brand->id.'" action="/superadmin/product_brand/'. $product_brand->id .'">
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
        return view('super_admin.brands.create',compact('title', 'page_detail'));
    }


    public function store(ProductBrandStore $request)
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

            if(!empty($request->image)) {
                // Start of Single Images Part
                $path_info = getPathInfo(['productBrand','parent', 'images', 'original',$productBrand->id]);
                $thumbnail_path_info = getPathInfo(['productBrand', 'parent', 'images', 'thumbnail',$productBrand->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);


                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $productBrand->slug . '-' . rand() . '-' . uniqid() .  '.' .$extension;


                $original_imageLocation = 'backend/uploads/productBrand/parent/images/original/'.$productBrand->id.'/'.$setName;
                Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;
                // for thumbnail image
                $thumbnail_imageLocation = 'backend/uploads/productBrand/parent/images/thumbnail/' . $productBrand->id . '/' . $setName;
                Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;


                $productBrand->image = $original_imageLocation;
                $productBrand->image_url = $image_url;
                $productBrand->thumbnail_image = $thumbnail_imageLocation;
                $productBrand->thumbnail_image_url = $thumbnail_image_url;
                $productBrand->save();

            }
            $allowed_vendor_count = AdminConfig::where('name','allowed_vendor_count')->value('value');
            if ($allowed_vendor_count == 1) {
                $parent_product_brand_tbl_id = $productBrand->id;
                $storeBrand = $this->_storeBrand($request,$parent_product_brand_tbl_id);

            }
                // Commit Transaction
                DB::commit();
            return redirect()->action('SuperAdmin\ProductBrandController@index')->with('success', 'Product Brand Created Success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }
    private function _storeBrand($request,$parent_product_brand_tbl_id)
    {
        if($request->slug){
            $slug = Str::slug($request->slug, '-');
        }else{
            $slug = Str::slug($request->name, '-');
        }
        if($slug) {
            $slug_name = ProductBrand::where(['vendor_id' => 0, 'slug' => $slug])->value('slug');
            if ($slug_name){
                return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
            }
        }
        if($request->name) {
            $name = ProductBrand::where(['vendor_id' => 0, 'name' => $request->name])->value('name');
            if ($name){
                return redirect()->back()->with('error', 'Duplicate name found please insert another name!');
            }
        }

        $request['status'] = $request->status ?? 0;
        $request['vendor_id'] = 0;
        $request['created_by'] =  auth()->user()->id;
        $request['updated_by'] =  auth()->user()->id;

        $productBrand = ProductBrand::create([
            'name' => $request->name,
            'slug' => $slug,
            'website' => $request->website,
            'vendor_id' => $request->vendor_id,
            'status' => $request->status,
            'is_approved' => 1,
            'parent_product_brand_tbl_id' => $parent_product_brand_tbl_id ?? 0,
            'created_by' => $request->created_by,
            'updated_by' => $request->updated_by,
        ]);

        if(!empty($request->image)) {
            // Start of Single Images Part
            $path_info = getPathInfo(['productBrand', 'vendor', 'images', 'original',$productBrand->id]);
            $thumbnail_path_info = getPathInfo(['productBrand','vendor', 'images', 'thumbnail',$productBrand->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);

            $img = $request->image;
            $img = json_decode($img, true);
            $img_type = explode('/', $img['type']);
            $extension = $img_type[1];
            $setName = $productBrand->slug . '-' . rand() . '-' . uniqid() .  '.' .$extension;


            $original_imageLocation = 'backend/uploads/productBrand/vendor/images/original/'.$productBrand->id.'/'.$setName;
            Image::make($img['data'])->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;
            // for thumbnail image
            $thumbnail_imageLocation = 'backend/uploads/productBrand/vendor/images/thumbnail/' . $productBrand->id . '/' . $setName;
            Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

            $productBrand->image = $original_imageLocation;
            $productBrand->image_url = $image_url;
            $productBrand->thumbnail_image = $thumbnail_imageLocation;
            $productBrand->thumbnail_image_url = $thumbnail_image_url;
            $productBrand->save();

        }

    }

    public function show (ProductBrand $productBrand)
    {

    }

    public function edit($id)
    {
        $title = 'Edit Product Brand';
        $page_detail = 'Edit a product brand';
        $productBrand = ParentProductBrand::where('id',$id)->first();
        return view('super_admin.brands.edit', compact('productBrand','title', 'page_detail'));
    }

    public function update(ProductBrandUpdate $request,  $id)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $productBrand = ParentProductBrand::where('id',$id)->first();
            if($request->slug){
                $slug = Str::slug($request->slug, '-');
            }else{
                $slug = Str::slug($request->name, '-');
            }
            if($slug) {
                    $slug_name = ParentProductBrand::where([
                        'slug' => $slug
                    ])->where('id', '<>' , $productBrand->id)
                        ->value('slug');
                    if ($slug_name){
                        return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
                    }
                }
            if($request->name) {
                    $name = ParentProductBrand::where([
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
            if(!empty($request->image)) {
                // Start of Single Images Part
                    if ($productBrand->image)
                    {
                        if (file_exists($productBrand->image)){
                            unlink($productBrand->image);
                            unlink($productBrand->thumbnail_image);
                        }
                    }
                    $path_info = getPathInfo(['productBrand','parent', 'images', 'original',$productBrand->id]);
                    $thumbnail_path_info = getPathInfo(['productBrand','parent', 'images', 'thumbnail',$productBrand->id]);
                    makeDirectory($path_info,0755);
                    makeDirectory($thumbnail_path_info,0755);

                    $img = $request->image;
                    $img = json_decode($img, true);
                    $img_type = explode('/', $img['type']);
                    $extension = $img_type[1];
                    $setName = $productBrand->slug . '-' . rand() . '-' . uniqid() .  '.' .$extension;


                    $original_imageLocation = 'backend/uploads/productBrand/parent/images/original/'.$productBrand->id.'/'.$setName;
                    Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                    $image_url = URL::to('/'). '/' . $original_imageLocation;
                    // for thumbnail image
                    $thumbnail_imageLocation = 'backend/uploads/productBrand/parent/images/thumbnail/' . $productBrand->id . '/' . $setName;
                    Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                    $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

                    $productBrand->image = $original_imageLocation;
                    $productBrand->image_url = $image_url;
                    $productBrand->thumbnail_image = $thumbnail_imageLocation;
                    $productBrand->thumbnail_image_url = $thumbnail_image_url;
                    $productBrand->save();
                }

            // Commit Transaction
            DB::commit();
            return redirect()->action('SuperAdmin\ProductBrandController@index')->with('success', 'ProductBrand Updated Success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

         return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function destroy($id)
    {
        $productBrand = ParentProductBrand::where('id',$id)->first();

            if ($productBrand->image)
            {
                if (file_exists($productBrand->image)){
                    unlink($productBrand->image);
                    unlink($productBrand->thumbnail_image);

                }
            }
            $productBrand->delete();
            return redirect()->back()->with('success', 'Product Brand Deleted Success !');


    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $productBrand = ParentProductBrand::where('id', $request->id)->first();
        if ($productBrand)
        {
            $productBrand->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }


    //Start of  Brand Approval Part as parent

    public function productBrandApproval()
    {
        $title = 'Approve Product Brands';
        $page_detail = 'Approve a Product Brand';
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('super_admin.brands.brand_approval_panel', compact( 'title', 'page_detail','vendors'));
    }

    public function getChildListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }

        $data = ProductBrand::with('vendor');
        if(!empty($request->vendor_id)){
            $data->where('vendor_id', $request->vendor_id);
        }
        if(!empty($request->status_id) ){
            if ($request->status_id == 1){
                $data->whereRaw('COALESCE(parent_product_brand_tbl_id, 0) != 0');
                $data->where('is_approved', '=', 1);
            }
            elseif ($request->status_id == 2){
                $data->whereRaw('COALESCE(parent_product_brand_tbl_id, 0) != 0');
                $data->where('is_approved', '=', 0);
            }
            elseif ($request->status_id == 3){
                $data->whereRaw('COALESCE(parent_product_brand_tbl_id, 0) = 0');
                $data->where('is_approved', '=', 0);
            }
        }
        $product_brands = $data->get();
        return DataTables::of($product_brands)
            ->addIndexColumn()

            ->editColumn('status', function ($product_brand) {
                if ($product_brand->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_brand->status.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_brand->status.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('parent_name', function ($product_brand) {
                if (!empty($product_brand->parentBrand)) return $product_brand->parentBrand->name ;
                return 'N/A';
            })
            ->editColumn('is_approved', function ($product_brand) {
                if ($product_brand->is_approved == 1) return '<span href="#0" statusCode="'.$product_brand->is_approved.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-success">Approved</span>';
                return '<span href="#0" statusCode="'.$product_brand->is_approved.'" data_id="'.$product_brand->id.'"   class="badge cursor-pointer badge-danger">Disapproved</span>';
            })
            ->addColumn('checkbox', function ($product_brand) {
                return '<div class="text-center form-check custom_checkbox">
                                        <input  name="ids[]" class="form-check-input checkbox-brand export-approve-brand" type="checkbox" id="checkbox-brand-'.$product_brand->id.'" data-id="'.$product_brand->id.'" value="'.$product_brand->id.'">
                                        <label class="form-check-label" for="checkbox-brand-'.$product_brand->id.'"></label>
                                    </div>
                        ';
            })
            ->rawColumns(['status','is_approved','checkbox','parent_name'])
            ->make(true);

    }


    public function productBrandApprovalStatus(Request $request)
    {
        if ($request->brandsInPack){

                foreach ($request->brandsInPack as $id){
                    $brand = ProductBrand::where([
                        'id' => $id,
                    ])->first();
                    $brand->is_approved = 1;
                    $brand->updated_by =  Auth::id();
                    $brand->save();
                }

        }
        return response()->json(['success' => true]);

    }
    public function productBrandDisApprovalStatus(Request $request)
    {
        if ($request->brandsInPack){

                foreach ($request->brandsInPack as $id){
                    $brand = ProductBrand::where([
                        'id' => $id,
                    ])->with('brandProducts')->first();
                    if ($brand->brandProducts->count() > 0 ) {
                        return response()->json(['success' => false,'msg'=>'This Brand can not be disapprove, because of dependency!']);
                    }else{
                        $brand->is_approved = 0;
                        $brand->updated_by =  Auth::id();
                        $brand->updated_at =  Carbon::now();
                        $brand->save();
                    }
                }

        }
        return response()->json(['success' => true]);

    }

    public function approveAsNewParent(Request $request){

        if ($request->brandsInPack ){
            foreach ($request->brandsInPack as $id){

                $brand = ProductBrand::where([
                    'id' => $id,
                ])->first();
                if($brand->slug) {
                    $slug_name = ParentProductBrand::where(['slug' => $brand->slug])->value('slug');
                    if ($slug_name){
                        return response()->json(['success' => false,'msg'=>'This Brand already exist, please select another one!']);
                    }
                }
                if($brand->name) {
                    $name = ParentProductBrand::where(['name' => $brand->name])->value('name');
                    if ($name){
                        return response()->json(['success' => false,'msg'=>'This Brand already exist, please select another one!']);
                    }
                }
                if($brand->parent_category_id){
                    $parentApproveCheck = ProductBrand::where([
                        'id' => $brand->parent_category_id,
                        'is_approved' => 1,
                    ])->first();
                    if (!$parentApproveCheck){
                        return redirect()->back()->with('error', 'Parent category not approve yet for '.$brand->name);
                    }
                }

                if ($brand)
                {
                    $parentBrandFields = [
                        'name' => $brand->name,
                        'slug' => $brand->slug,
                        'image' => $brand->image,
                        'image_url' => $brand->image_url,
                        'thumbnail_image' => $brand->thumbnail_image,
                        'thumbnail_image_url' => $brand->thumbnail_image_url,
                        'website' => $brand->website,
                        'status' => $brand->status,
                        'created_by' => auth()->id(),
//                        'updated_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
//                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                    $parentBrand = ParentProductBrand::create($parentBrandFields);
                    if($parentBrand->id){
                        $brand->update(['is_approved' => 1,'parent_product_brand_tbl_id' => $parentBrand->id,'updated_by' => auth()->id()]);
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
        $parentBrands = ParentProductBrand::where([
            ['status', 1],
        ])->get();

        return DataTables::of($parentBrands)
            ->addIndexColumn()
            ->editColumn('status', function ($parentBrand) {
                if ($parentBrand->status == 1) return '<span href="#0"  statusCode="'.$parentBrand->status.'" data_id="'.$parentBrand->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0"  statusCode="'.$parentBrand->status.'" data_id="'.$parentBrand->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($parentBrand) {
                return '<div class="btn-group">
                        <a href="#" class="btn btn-sm btn-info btn-icon parentBrandSelect" data-parent_brand_id="'.$parentBrand->id.'" data-toggle="tooltip" data-placement="auto" title="Select" data-original-title="Select"><i class="fa fa-check"></i></a>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }


    public function modalParentBrandStore(Request $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
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
                $slug_name = ParentProductBrand::where(['slug' => $request->slug])->value('slug');
                if ($slug_name){
                    return response()->json(['error' => 'Duplicate slug found please insert another slug!'], 400);
                }
            }
            $request['status'] = $request->status ?? 0;
            $request['created_by'] =  auth()->user()->id;

            if($request->slug){
                $slug = Str::slug($request->slug, '-');
            }else{
                $slug = Str::slug($request->name, '-');
            }


            $productBrand = ParentProductBrand::create([
                'name' => $request->name,
                'slug' => $slug,
                'website' => $request->website,
                'status' => 1,
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
            // Commit Transaction
            DB::commit();
            return response()->json('true');


        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();
            return response()->json('false');
//            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    public function parentBrandMap(Request $request)
    {
        if ($request->brandsInPack){
            foreach ($request->brandsInPack as $brandId){
                ProductBrand::where('id', $brandId)->update([
                    'parent_product_brand_tbl_id' => $request->parent_brand_id,
                    'is_approved' => 1,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
    public function parentBrandUnMap(Request $request)
    {
        if ($request->brandsInPack){
            foreach ($request->brandsInPack as $brandId){
                ProductBrand::where('id', $brandId)->update([
                    'parent_product_brand_tbl_id' => null,
                    'is_approved' => 0,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        return response()->json(['success' => true]);
    }


}
