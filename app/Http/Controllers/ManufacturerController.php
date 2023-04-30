<?php

namespace App\Http\Controllers;

use App\AdminConfig;
use App\ParentManufacturer;
use App\Vendor;
use App\Country;
use App\Http\Requests\Manufacturer\ManufacturerStore;
use App\Http\Requests\Manufacturer\ManufacturerUpdate;
use App\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
class ManufacturerController extends Controller
{
    public function index()
    {

        $title = 'Manufacturer';
        $page_detail = 'List of manufacturer';
        return view('manufacturers.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $manufacturers = Manufacturer::where('vendor_id', auth()->user()->vendor_id)->latest()->get();
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
                if ($manufacturer->image)  return '
                        <div class="pop_img" data-img="'.asset($manufacturer->image).'">
                            <img width="50"
                                 src="'.asset($manufacturer->image).'"
                                 alt="image">
                        </div>
                ';
                return '
                        <div>
                            No Image
                        </div>
                ';
            })
            ->addColumn('action', function ($manufacturer) {
                return '<div class="btn-group">
                        <a href="/admin/manufacturer/' . $manufacturer->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteManufacture('.$manufacturer->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteManufacturerForm-'.$manufacturer->id.'" action="/admin/manufacturer/' . $manufacturer->id . '">
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
        $page_detail = 'Create a Manufacturer for your vendor';
        $countries = Country::all();
        return view('manufacturers.create', compact('title', 'page_detail','countries'));
    }

    public function store(ManufacturerStore $request)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $allowed_vendor_count = AdminConfig::where('name','allowed_vendor_count')->value('value');
//            if ($allowed_vendor_count == 1) {
//                $storeManufacturer = $this->_storeManufacturer($request);
//                if ($storeManufacturer->id){
//                    $is_approved = 1;
//                    $parent_manufacturer_tbl_id = $storeManufacturer->id;
//                }
//            }
            if($request->name) {
                $manufacturer_name = Manufacturer::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
                if ($manufacturer_name){
                    return redirect()->back()->with('error', 'Duplicate manufacturer name found please insert another name!');
                }
            }
            $parent_manufacturer_id = null;
//            if ($request->parent_manufacturer_id){
//                $parent_manufacturer_id = $request->parent_manufacturer_id;
//            }else{
//                $checkParentManufacturer = ParentManufacturer::where('name', $request->name)->value('id');
//                if ($checkParentManufacturer){
//                    $parent_manufacturer_id = $checkParentManufacturer;
//                }
//            }
            $request['name'] = $request->name;
            $request['email'] = $request->email;
            $request['phone'] = $request->phone;
            $request['country_name'] = $request->country_name;
            $request['description'] = $request->description;
            $request['website'] = $request->website;
            $request['status'] = $request->status ?? 0;
            $request['is_approved'] = $is_approved ?? 1;
            $request['parent_manufacturer_tbl_id'] = $parent_manufacturer_tbl_id ?? $parent_manufacturer_id;
            $request['vendor_id'] = auth()->user()->vendor_id;
            $request['created_by'] = auth()->user()->id;
            $request['updated_by'] = auth()->user()->id;
            $manufacturer = Manufacturer::create($request->except('_token', 'image'));

            if ($request->hasFile('image')){
                $path_info = getPathInfo(['manufacturer', 'vendor', 'images', 'original',$manufacturer->id]);
                $thumbnail_path_info = getPathInfo(['manufacturer', 'vendor', 'images', 'thumbnail',$manufacturer->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);


                $rootImage = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                $setName = $manufacturer->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;
                $original_imageLocation = 'backend/uploads/manufacturer/vendor/images/original/'.$manufacturer->id.'/'.$setName;
                Image::make($rootImage)->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;


                // for thumbnail image
                $thumbnail_imageLocation = 'backend/uploads/manufacturer/vendor/images/thumbnail/' . $manufacturer->id . '/' . $setName;
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
            return redirect()->action('ManufacturerController@index')->with('success', 'Manufacturer Create Success !');
        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }


    }

    private function _storeManufacturer($request)
    {

        if($request->name) {
            $manufacturer_name = ParentManufacturer::where(['name' => $request->name])->value('name');
            if ($manufacturer_name){
                return redirect()->back()->with('error', 'Duplicate manufacturer name found please insert another name!');
            }
        }
        if($request->email) {
            $email = ParentManufacturer::where(['email' => $request->email])->value('email');
            if ($email){
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
        return $manufacturer;


    }

    public function show(Manufacturer $manufacturer)
    {
        if ($manufacturer->vendor_id == auth()->user()->vendor_id) {
            return view('manufacturers.view', compact('manufacturer'));
        } else {
            abort(404);
        }
    }

    public function edit(Manufacturer $manufacturer)
    {
        $title = 'Edit Manufacturer';
        $page_detail = 'Edit Manufacturer for your vendor';
        // only allow auth user and admin
        if ($manufacturer->vendor_id == auth()->user()->vendor_id) {
            $countries = Country::all();
            return view('manufacturers.edit', compact('manufacturer', 'countries','title', 'page_detail'));
        } else {
            abort(404);
        }

    }

    public function update(ManufacturerUpdate $request, Manufacturer $manufacturer)
    {
        // Begin Transaction
        DB::beginTransaction();

        try {
            if($request->name) {
                $manufacturer_name = Manufacturer::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'name' => $request->name
                ])->where('id', '<>' , $manufacturer->id)
                    ->value('name');
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
            $request['vendor_id'] = auth()->user()->vendor_id;
            $request['updated_by'] = auth()->user()->id;
            $manufacturer->update($request->except('image'));


//            if (isset($request->image)){
                if ($request->hasFile('image')){
                // delete previous product images
                if ($manufacturer->image)
                {
                    if (file_exists($manufacturer->image)){
                        unlink($manufacturer->image);
                        unlink($manufacturer->thumbnail_image);
                    }
                }
                // End of delete product images
                $path_info = getPathInfo(['manufacturer', 'images', 'original',$manufacturer->id]);
                $thumbnail_path_info = getPathInfo(['manufacturer', 'images', 'thumbnail',$manufacturer->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);

                $rootImage = $request->file('image');
                $extension = $request->file('image')->getClientOriginalExtension();
                $setName = $manufacturer->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;
                $original_imageLocation = 'backend/uploads/manufacturer/images/original/'.$manufacturer->id.'/'.$setName;
                Image::make($rootImage)->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;


                // for thumbnail image

                $thumbnail_imageLocation = 'backend/uploads/manufacturer/images/thumbnail/' . $manufacturer->id . '/' . $setName;
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
            return redirect()->action('ManufacturerController@index')->with('success', 'Manufacturer Updated Success !');
        } catch (Exception $exc) {
            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }

    }

    public function destroy(Manufacturer $manufacturer)
    {
        // only allow auth user and admin
        if ($manufacturer->vendor_id == auth()->user()->vendor_id) {
            if ($manufacturer->image != null) {
                if (file_exists($manufacturer->image)) {
                    unlink($manufacturer->image);
                }
            }

            if ($manufacturer->products->count() > 0) {
                return redirect()->back()->with('warning', $manufacturer->name . ' not allow to delete');
            }

            $manufacturer->delete();
            return redirect()->back()->with('success', 'Manufacture deleted success !');
        } else {
            abort(404);
        }

    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $manufacturer = Manufacturer::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();

        if ($manufacturer) {
            $manufacturer->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }

    public function getliveSearchByAjax(Request $request)
    {
        $search = $request->search;

        if($search == ''){
            $manufacturers = ParentManufacturer::
            where('name', 'like', '%' .$search . '%')
                ->select('name','id')
                ->get();
        }else{
            $manufacturers = ParentManufacturer::
            where('name', 'like', '%' .$search . '%')
                ->select('name','id')
                ->get();
        }
        $response = array();
        foreach($manufacturers as $manufacturer){
            $response[] = array("label"=>$manufacturer->name,"manufacturer_id"=>$manufacturer->id);
        }

        echo json_encode($response);
        exit;
    }
}
