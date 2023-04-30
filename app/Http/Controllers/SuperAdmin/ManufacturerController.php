<?php

namespace App\Http\Controllers\SuperAdmin;
use App\AdminConfig;
use App\Http\Controllers\Controller;
use App\Manufacturer;
use App\ParentManufacturer;
use App\Vendor;
use App\Country;
use App\Http\Requests\Manufacturer\ManufacturerStore;
use App\Http\Requests\Manufacturer\ManufacturerUpdate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
class ManufacturerController extends Controller
{
    public function index()
    {
        $title = 'Manufacturer';
        $page_detail = 'List of manufacturers';
        return view('super_admin.manufacturers.index', compact('title', 'page_detail'));
    }


    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $manufacturers = ParentManufacturer::latest()->get();
        foreach ($manufacturers as $manufacturer){
            $manufacturer->description = Str::limit($manufacturer->description,50);
        }
        return DataTables::of($manufacturers)
            ->addIndexColumn()

            ->editColumn('status', function ($manufacturer) {
                if ($manufacturer->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$manufacturer->status.'" data_id="'.$manufacturer->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$manufacturer->status.'" data_id="'.$manufacturer->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($manufacturer) {
                return '
                        <div class="pop_img" data-img="'.asset($manufacturer->image).'">
                            <img width="50"
                                 src="'.asset($manufacturer->image).'"
                                 alt="image">
                        </div>
                ';
            })
            ->addColumn('action', function ($manufacturer) {
                return '<div class="btn-group">
                        <a href="/superadmin/manufacturer/' . $manufacturer->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteManufacture('.$manufacturer->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteManufacturerForm-'.$manufacturer->id.'" action="/superadmin/manufacturer/' . $manufacturer->id . '">
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
        $title = 'Create Manufacturer';
        $page_detail = 'Create a Manufacturer';
        $countries = Country::all();
        return view('super_admin.manufacturers.create', compact('title', 'page_detail','countries'));
    }

    public function store(ManufacturerStore $request)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->name) {
                $manufacturer_name = ParentManufacturer::where(['name' => $request->name])->value('name');
                if ($manufacturer_name){
                    return redirect()->back()->with('error', 'Duplicate manufacturer name found please insert another name!');
                }
            }
            $request['name'] = $request->name;
            $request['email'] = $request->email;
            $request['phone'] = $request->phone;
            $request['country_name'] = $request->country_name;
            $request['description'] = $request->description;
            $request['website'] = $request->website;
            $request['status'] = $request->status ?? 0;
            $request['created_by'] = auth()->user()->id;
            $manufacturer = ParentManufacturer::create($request->except('_token', 'image'));

            if(!empty($request->image)) {
                // Start of Single Images Part
                $path_info = getPathInfo(['manufacturer','parent', 'images', 'original',$manufacturer->id]);
                $thumbnail_path_info = getPathInfo(['manufacturer','parent', 'images', 'thumbnail',$manufacturer->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $manufacturer->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;


                $original_imageLocation = 'backend/uploads/manufacturer/parent/images/original/'.$manufacturer->id.'/'.$setName;
                Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;
// for thumbnail image
                $thumbnail_imageLocation = 'backend/uploads/manufacturer/parent/images/thumbnail/' . $manufacturer->id . '/' . $setName;
                Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;


                $manufacturer->image = $original_imageLocation;
                $manufacturer->image_url = $image_url;
                $manufacturer->thumbnail_image = $thumbnail_imageLocation;
                $manufacturer->thumbnail_image_url = $thumbnail_image_url;
                $manufacturer->save();
        }

            $allowed_vendor_count = AdminConfig::where('name','allowed_vendor_count')->value('value');
            if ($allowed_vendor_count == 1) {
                $parent_manufacturer_tbl_id = $manufacturer->id;
                $storeManufacturer = $this->_storeManufacturer($request,$parent_manufacturer_tbl_id);

            }

                // Commit Transaction
                DB::commit();
            return redirect()->action('SuperAdmin\ManufacturerController@index')->with('success', 'Manufacturer Create Success !');
        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }


    }


    private function _storeManufacturer($request,$parent_manufacturer_tbl_id)
    {

        if($request->name) {
            $manufacturer_name = Manufacturer::where(['vendor_id' => 0, 'name' => $request->name])->value('name');
            if ($manufacturer_name){
                return redirect()->back()->with('error', 'Duplicate manufacturer name found please insert another name!');
            }
        }
        if($request->email) {
            $manufacturer_email = Manufacturer::where(['vendor_id' => 0, 'email' => $request->name])->value('email');
            if ($manufacturer_email){
                return redirect()->back()->with('error', 'Duplicate manufacturer email found please insert another email!');
            }
        }
        $request['name'] = $request->name;
        $request['email'] = $request->email;
        $request['phone'] = $request->phone;
        $request['country_name'] = $request->country_name;
        $request['description'] = $request->description;
        $request['website'] = $request->website;
        $request['status'] = $request->status ?? 0;
        $request['is_approved'] = 1;
        $request['parent_manufacturer_tbl_id'] = $parent_manufacturer_tbl_id ?? 0;
        $request['vendor_id'] = 0;
        $request['created_by'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;
        $manufacturer = Manufacturer::create($request->except('_token', 'image'));

        if(!empty($request->image)) {
            // Start of Single Images Part
            $path_info = getPathInfo(['manufacturer', 'vendor', 'images', 'original',$manufacturer->id]);
            $thumbnail_path_info = getPathInfo(['manufacturer','vendor', 'images', 'thumbnail',$manufacturer->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);


            $img = $request->image;
            $img = json_decode($img, true);
            $img_type = explode('/', $img['type']);
            $extension = $img_type[1];
            $setName = $manufacturer->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;


            $original_imageLocation = 'backend/uploads/manufacturer/vendor/images/original/'.$manufacturer->id.'/'.$setName;
            Image::make($img['data'])->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;
            // for thumbnail image
            $thumbnail_imageLocation = 'backend/uploads/manufacturer/vendor/images/thumbnail/' . $manufacturer->id . '/' . $setName;
            Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;


            $manufacturer->image = $original_imageLocation;
            $manufacturer->image_url = $image_url;
            $manufacturer->thumbnail_image = $thumbnail_imageLocation;
            $manufacturer->thumbnail_image_url = $thumbnail_image_url;
            $manufacturer->save();
        }
        return $manufacturer;


    }

    public function show()
    {

    }

    public function edit($id)
    {
        $title = 'Edit Manufacturer';
        $page_detail = 'Edit Manufacturer';
        // only allow auth user and admin
        $manufacturer = ParentManufacturer::where('id',$id)->first();
            $countries = Country::all();
            return view('super_admin.manufacturers.edit', compact('manufacturer', 'countries','title', 'page_detail'));
    }

    public function update(ManufacturerUpdate $request, $id)
    {
        // Begin Transaction
        DB::beginTransaction();

        try {
            $manufacturer = ParentManufacturer::where('id',$id)->first();
            if($request->name) {
                $manufacturer_name = ParentManufacturer::where([
                    'name' => $request->name
                ])->where('id', '<>' , $manufacturer->id)
                    ->value('name');
                if ($manufacturer_name){
                    return redirect()->back()->with('error', 'Duplicate manufacturer name found please insert another name!');
                }
            }
            if($request->email) {
                $manufacturer_email = ParentManufacturer::where([
                    'email' => $request->email
                ])->where('id', '<>' , $manufacturer->id)
                    ->value('email');
                if ($manufacturer_email){
                    return redirect()->back()->with('error', 'Duplicate manufacturer email found please insert another email!');
                }
            }

            $request['name'] = $request->name;
            $request['email'] = $request->email;
            $request['phone'] = $request->phone;
            $request['country_name'] = $request->country_name;
            $request['description'] = $request->description;
            $request['website'] = $request->website;
            $request['status'] = $request->status ?? 0;
            $request['updated_by'] = auth()->user()->id;
            $manufacturer->update($request->except('image'));


            if(!empty($request->image)) {
                // Start of Single Images Part
                if ($manufacturer->image)
                {
                    if (file_exists($manufacturer->image)){
                        unlink($manufacturer->image);
                        unlink($manufacturer->thumbnail_image);
                    }
                }
                // End of delete product images
                $path_info = getPathInfo(['manufacturer','parent', 'images', 'original',$manufacturer->id]);
                $thumbnail_path_info = getPathInfo(['manufacturer','parent', 'images', 'thumbnail',$manufacturer->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);


                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $manufacturer->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                $original_imageLocation = 'backend/uploads/manufacturer/parent/images/original/'.$manufacturer->id.'/'.$setName;
                Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;
                // for thumbnail image
                $thumbnail_imageLocation = 'backend/uploads/manufacturer/parent/images/thumbnail/' . $manufacturer->id . '/' . $setName;
                Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;
                $manufacturer->image = $original_imageLocation;
                $manufacturer->image_url = $image_url;
                $manufacturer->thumbnail_image = $thumbnail_imageLocation;
                $manufacturer->thumbnail_image_url = $thumbnail_image_url;
                $manufacturer->save();

            }
            // Commit Transaction
            DB::commit();
            return redirect()->action('SuperAdmin\ManufacturerController@index')->with('success', 'Manufacturer Updated Success !');
        } catch (Exception $exc) {
            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }

    }

    public function destroy($id)
    {

        $manufacturer = ParentManufacturer::where('id',$id)->first();
            if ($manufacturer->image != null) {
                if (file_exists($manufacturer->image)) {
                    unlink($manufacturer->image);
                    unlink($manufacturer->thumbnail_image);
                }
            }
            $manufacturer->delete();
            return redirect()->back()->with('success', 'Manufacture deleted success !');


    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $manufacturer = ParentManufacturer::where('id', $request->id)->first();

        if ($manufacturer) {
            $manufacturer->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }



    //Start of  Manufacturer Approval Part as parent

    public function manufacturerApproval()
    {
        $title = 'Approve Manufacturers';
        $page_detail = 'Approve a Manufacturer';
        $countries = Country::all();
        $vendors = Vendor::select('id','name')
            ->where([
                ['status', 1],
            ])
            ->get();
        return view('super_admin.manufacturers.manufacturer_approval_panel', compact( 'title', 'page_detail','vendors','countries'));
    }

    public function getChildListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $data = Manufacturer::with('vendor')->with('parentManufacturer');
        if(!empty($request->vendor_id)){
            $data->where('vendor_id', $request->vendor_id);
        }
        if(!empty($request->status_id) ){
            if ($request->status_id == 1){
                $data->whereRaw('COALESCE(parent_manufacturer_tbl_id, 0) != 0');
                $data->where('is_approved', '=', 1);
            }
            elseif ($request->status_id == 2){
                $data->whereRaw('COALESCE(parent_manufacturer_tbl_id, 0) != 0');
                $data->where('is_approved', '=', 0);
            }
            elseif ($request->status_id == 3){
                $data->whereRaw('COALESCE(parent_manufacturer_tbl_id, 0) = 0');
                $data->where('is_approved', '=', 0);
            }
        }
        $manufacturers = $data->get();
        return DataTables::of($manufacturers)
            ->addIndexColumn()

            ->editColumn('parent_name', function ($manufacturer) {
                if (!empty($manufacturer->parentManufacturer)) return $manufacturer->parentManufacturer->name ;
                return 'N/A';
            })
            ->editColumn('status', function ($manufacturer) {
                if ($manufacturer->status == 1) return '<span href="#0"  statusCode="'.$manufacturer->status.'" data_id="'.$manufacturer->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" statusCode="'.$manufacturer->status.'" data_id="'.$manufacturer->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('is_approved', function ($manufacturer) {
                if ($manufacturer->is_approved == 1) return '<span href="#0" statusCode="'.$manufacturer->is_approved.'" data_id="'.$manufacturer->id.'"   class="badge cursor-pointer badge-success">Approved</span>';
                return '<span href="#0" statusCode="'.$manufacturer->is_approved.'" data_id="'.$manufacturer->id.'"   class="badge cursor-pointer badge-danger">Disapproved</span>';
            })
            ->addColumn('checkbox', function ($manufacturer) {
                return '<div class="text-center form-check custom_checkbox">
                                        <input  name="ids[]" class="form-check-input checkbox-manufacturer export-approve-manufacturer" type="checkbox" id="checkbox-manufacturer-'.$manufacturer->id.'" data-id="'.$manufacturer->id.'" value="'.$manufacturer->id.'">
                                        <label class="form-check-label" for="checkbox-manufacturer-'.$manufacturer->id.'"></label>
                                    </div>
                        ';
            })
            ->rawColumns(['status','is_approved','checkbox'])
            ->make(true);

    }
    public function manufacturerApprovalStatus(Request $request)
    {
        if ($request->manufacturersInPack){

            foreach ($request->manufacturersInPack as $id){
                $manufacturer = Manufacturer::where([
                    'id' => $id,
                ])->first();
                $manufacturer->is_approved = 1;
                $manufacturer->updated_by =  Auth::id();
                $manufacturer->save();
            }

        }
        return response()->json(['success' => true]);

    }
    public function manufacturerDisApprovalStatus(Request $request)
    {
        if ($request->manufacturersInPack){

            foreach ($request->manufacturersInPack as $id){
                $manufacturer = Manufacturer::where([
                    'id' => $id,
                ])->with('products')->first();
                if ($manufacturer->products->count() > 0 ) {
                    return response()->json(['success' => false,'msg'=>'This Manufacturer can not be disapprove, because of dependency!']);
                }else{
                    $manufacturer->is_approved = 0;
                    $manufacturer->updated_by =  Auth::id();
                    $manufacturer->updated_at =  Carbon::now();
                    $manufacturer->save();
                }
            }

        }
        return response()->json(['success' => true]);

    }

    public function approveAsNewParent(Request $request){

        if ($request->manufacturersInPack ){
            foreach ($request->manufacturersInPack as $id){

                $manufacturer = Manufacturer::where([
                    'id' => $id,
                ])->first();

                if($manufacturer->name) {
                    $name = ParentManufacturer::where(['name' => $manufacturer->name])->value('name');
                    if ($name){
                        return response()->json(['success' => false,'msg'=>'This Manufacturer already exist, please select another one!']);
                    }
                }

                if ($manufacturer)
                {
                    $parentManufacturerFields = [
                        'name' => $manufacturer->name,
                        'email' => $manufacturer->email,
                        'phone' => $manufacturer->phone,
                        'website' => $manufacturer->website,
                        'description' => $manufacturer->description,
                        'image' => $manufacturer->image,
                        'image_url' => $manufacturer->image_url,
                        'thumbnail_image' => $manufacturer->thumbnail_image,
                        'thumbnail_image_url' => $manufacturer->thumbnail_image_url,
                        'status' => $manufacturer->status,
                        'country_name' => $manufacturer->country_name,
                        'created_by' => auth()->id(),
                        'created_at' => date('Y-m-d H:i:s'),
                    ];
                    $parentManufacturer = ParentManufacturer::create($parentManufacturerFields);
                    if($parentManufacturer->id){
                        $manufacturer->update(['is_approved' => 1,'parent_manufacturer_tbl_id' => $parentManufacturer->id,'updated_by' => auth()->id()]);
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
        $parentManufacturers = ParentManufacturer::where([
            ['status', 1],
        ])->get();

        return DataTables::of($parentManufacturers)
            ->addIndexColumn()
            ->editColumn('status', function ($parentManufacturer) {
                if ($parentManufacturer->status == 1) return '<span href="#0"  statusCode="'.$parentManufacturer->status.'" data_id="'.$parentManufacturer->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0"  statusCode="'.$parentManufacturer->status.'" data_id="'.$parentManufacturer->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($parentManufacturer) {
                return '<div class="btn-group">
                        <a href="#" class="btn btn-sm btn-info btn-icon parentManufacturerSelect" data-parent_manufacturer_id="'.$parentManufacturer->id.'" data-toggle="tooltip" data-placement="auto" title="Select" data-original-title="Select"><i class="fa fa-check"></i></a>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }


    public function modalParentManufacturerStore(Request $request)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {

            $rules = [
                'name' => 'required|string',
                'country_name' => 'required|string',
                'email' => 'nullable|string|unique:parent_manufacturers,email',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if($request->name) {
                $manufacturer_name = ParentManufacturer::where(['name' => $request->name])->value('name');
                if ($manufacturer_name){
                    return response()->json(['error' => 'Duplicate manufacturer name found please insert another name!'], 400);
                }
            }
            $request['name'] = $request->name;
            $request['email'] = $request->email;
            $request['phone'] = $request->phone;
            $request['country_name'] = $request->country_name;
            $request['description'] = $request->description;
            $request['website'] = $request->website;
            $request['status'] = 1;
            $request['created_by'] = auth()->user()->id;
            $manufacturer = ParentManufacturer::create($request->except('_token', 'image'));

            if ($request->hasFile('image')){
                $path_info = getPathInfo(['manufacturer','parent', 'images', 'original',$manufacturer->id]);
                $thumbnail_path_info = getPathInfo(['manufacturer','parent', 'images', 'thumbnail',$manufacturer->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);
                $rootImage = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                $setName = $manufacturer->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;
                $original_imageLocation = 'backend/uploads/manufacturer/parent/images/original/'.$manufacturer->id.'/'.$setName;
                Image::make($rootImage)->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;


                // for thumbnail image

                $thumbnail_imageLocation = 'backend/uploads/manufacturer/parent/images/thumbnail/' . $manufacturer->id . '/' . $setName;
                Image::make($rootImage)->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;

                $manufacturer->image = $original_imageLocation;
                $manufacturer->image_url = $image_url;
                $manufacturer->thumbnail_image = $thumbnail_imageLocation;
                $manufacturer->thumbnail_image_url = $thumbnail_image_url;
                $manufacturer->save();

            }
            // Commit Transaction
            DB::commit();
            return response()->json('true');
        } catch (\Exception $exc) {

            // Rollback Transaction
            DB::rollback();
            return response()->json('false');
        }

    }
    public function parentManufacturerMap(Request $request)
    {
        if ($request->manufacturersInPack){
            foreach ($request->manufacturersInPack as $manufacturerId){
                Manufacturer::where('id', $manufacturerId)->update([
                    'parent_manufacturer_tbl_id' => $request->parent_manufacturer_id,
                    'is_approved' => 1,
                    'updated_by' => Auth::id(),
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
    public function parentManufacturerUnMap(Request $request)
    {
        if ($request->manufacturersInPack){
            foreach ($request->manufacturersInPack as $manufacturerId){
                Manufacturer::where('id', $manufacturerId)->update([
                    'parent_manufacturer_tbl_id' => null,
                    'is_approved' => 0,
                    'updated_by' => Auth::id(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
}
