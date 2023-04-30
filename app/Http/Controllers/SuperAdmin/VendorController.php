<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Vendor;
use App\Country;
use App\AdminConfig;
use App\Manufacturer;
use App\Http\Requests\Vendor\VendorStore;
use App\Http\Requests\Vendor\VendorUpdate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use DataTables;

class VendorController extends Controller
{
    public function index()
    {
        $title = 'Vendor';
        $page_detail = 'List of Vendors';
        return view('super_admin.vendors.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $vendors = Vendor::latest()->get();

        return DataTables::of($vendors)
            ->addIndexColumn()

            ->editColumn('status', function ($vendor) {
                if ($vendor->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$vendor->status.'" data_id="'.$vendor->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$vendor->status.'" data_id="'.$vendor->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($vendor) {
                return '
                        <div class="pop_img" data-img="'.asset($vendor->logo).'">
                            <img width="50"
                                 src="'.asset($vendor->logo).'"
                                 alt="image">
                        </div>
                ';
            })
            ->addColumn('action', function ($vendor) {
                return '<div class="btn-group">
                        <a href="/superadmin/vendor/' . $vendor->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteVendor('.$vendor->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteVendorForm-'.$vendor->id.'" action="/superadmin/vendor/' . $vendor->id . '">
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
        $title = 'Create Vendor';
        $page_detail = 'Create a Vendor';
        return view('super_admin.vendors.create',compact('title', 'page_detail'));
    }


    public function store(VendorStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $request['status'] = $request->status ?? 0;
            $request['created_by'] =  auth()->user()->id;
            $request['updated_by'] =  auth()->user()->id;
            if($request->slug){
                $request['slug'] = Str::slug($request->slug, '-');
            }else{
                $request['slug'] = Str::slug($request->name, '-');
            }
            if(intval($request->status) == 1) {
                $allowed = AdminConfig::where('name','allowed_vendor_count')->value('value');
                if(intval($allowed) != 0){
                    if(Vendor::where('status',1)->count() >= $allowed){
                        return redirect()->back()->withInput()->with('error', 'Only ' . $allowed . ' vendor is allowed!');
                    }
                }
            }
            $vendor = Vendor::create($request->except('_token', 'image'));
            if(!empty($request->image)) {

                $logo_path_info = getPathInfo(['vendor','logo',$vendor->id]);
                $fav_path_info = getPathInfo(['vendor','favicon',$vendor->id]);
                makeDirectory($logo_path_info,0755);
                makeDirectory($fav_path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $vendor->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                //$setName =$vendor->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                $logo_imageLocation = 'backend/uploads/vendor/logo/'.$vendor->id.'/'.$setName;
                $fav_imageLocation = 'backend/uploads/vendor/favicon/'.$vendor->id.'/'.$setName;
                Image::make($img['data'])->resize(150,150, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($logo_path_info.'/'.$setName, 100);
                Image::make($img['data'])->resize(16,16, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($fav_path_info.'/'.$setName, 100);
                $logo_url = URL::to('/'). '/' . $logo_imageLocation;
                $favicon_url = URL::to('/'). '/' . $fav_imageLocation;

                $vendor->logo = $logo_imageLocation;
                $vendor->logo_url = $logo_url;
                $vendor->favicon = $fav_imageLocation;
                $vendor->favicon_url = $favicon_url;
                $vendor->save();
            }

            // Commit Transaction
            DB::commit();
            return redirect()->action('SuperAdmin\VendorController@index')->with('success', 'Vendor Create Success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function show (Vendor $vendor)
    {
        return view('super_admin.vendors.view', compact('vendor'));
    }

    public function edit(Vendor $vendor)
    {
        $title = 'Edit Vendor';
        $page_detail = 'Edit a Vendor';
        return view('super_admin.vendors.edit', compact('vendor','title', 'page_detail'));
    }

    public function update(VendorUpdate $request, Vendor $vendor)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $request['status'] = $request->status ?? 0;
            $request['updated_by'] =  auth()->user()->id;
            if($request->slug) {
                $slug_name = Vendor::where([
                    'slug' => $request->slug
                ])->where('id', '<>' , $vendor->id)
                    ->value('slug');
                if ($slug_name){
                    return redirect()->back()->with('error', 'Duplicate slug found please insert another slug!');
                }
            }
            if($request->slug){
                $request['slug'] = Str::slug($request->slug, '-');
            }else{
                $request['slug'] = Str::slug($request->name, '-');
            }
            if(intval($request->status) == 1) {
                $allowed = AdminConfig::where('name','allowed_vendor_count')->value('value');
                if(intval($allowed) != 0){
                    if(Vendor::where('status',1)->count() >= $allowed){
                        return redirect()->back()->withInput()->with('error', 'Only ' . $allowed . ' vendor is allowed!');
                    }
                }
            }
            $vendor->update($request->except('_token', 'image'));

            // logo
            if(!empty($request->image)) {
                if ($vendor->logo != null)
                {
                    if (file_exists($vendor->logo)){
                        unlink($vendor->logo);
                        unlink($vendor->favicon);
                    }
                }

                $logo_path_info = getPathInfo(['vendor','logo',$vendor->id]);
                $fav_path_info = getPathInfo(['vendor','favicon',$vendor->id]);
                makeDirectory($logo_path_info,0755);
                makeDirectory($fav_path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $vendor->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                //$setName =$vendor->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();
                $logo_imageLocation = 'backend/uploads/vendor/logo/'.$vendor->id.'/'.$setName;
                $fav_imageLocation = 'backend/uploads/vendor/favicon/'.$vendor->id.'/'.$setName;
                Image::make($img['data'])->resize(150,150, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($logo_path_info.'/'.$setName, 100);
                Image::make($img['data'])->resize(16,16, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })->save($fav_path_info.'/'.$setName, 100);
                $logo_url = URL::to('/'). '/' . $logo_imageLocation;
                $favicon_url = URL::to('/'). '/' . $fav_imageLocation;

                $vendor->logo = $logo_imageLocation;
                $vendor->logo_url = $logo_url;
                $vendor->favicon = $fav_imageLocation;
                $vendor->favicon_url = $favicon_url;
                $vendor->save();

            }


            // Commit Transaction
            DB::commit();

            return redirect()->action('SuperAdmin\VendorController@index')->with('success', 'Vendor Updated Success !');

        } catch (Exception $exc) {

                // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function destroy(Vendor $vendor)
    {
        if ($vendor->logo != null)
        {
            if (file_exists($vendor->logo)){
                unlink($vendor->logo);
            }
        }

        if ($vendor->favicon != null)
        {
            if (file_exists($vendor->favicon)){
                unlink($vendor->favicon);
            }
        }

        $vendor->delete();
        return redirect()->route('superadmin.vendor.index');
    }

    public function activeUnactive(Request $request)
    {
        if(intval($request->setStatus) == 1) {
            $allowed = AdminConfig::where('name','allowed_vendor_count')->value('value');
            if(intval($allowed) != 0){
                if(Vendor::where('status',1)->count() >= $allowed){
                    return response()->json(['success'=>false, 'msg'=>'Only ' . $allowed . ' vendor is allowed']);
                }
            }
        }
        $vendor =  Vendor::findOrFail($request->id);
        if ($vendor)
        {
            $vendor->update(['status' => $request->setStatus]);
            return response()->json(['success'=>true, 'msg'=>'Vendor status updated success']);
        }else{
            return response()->json(['success'=>false, 'msg'=>'Failed to update']);
        }

    }

    public function vendorDropdownList()
    {
        if (request()->ajax()) {
            $query = Vendor::query();
            $query->where('status', 1);
            if (!empty(auth()->user()->vendor_id)) {
                $query->where('id', auth()->user()->vendor_id);

            }
            if (!empty(trim(request()->search))) {
                $query->where('name', 'like', '%' . trim(request()->search) . '%');
            }
            $final = $query->pluck('name','id')->toArray();
            return response()->json($final, Response::HTTP_OK);
        }
    }
}
