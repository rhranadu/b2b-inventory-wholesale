<?php

namespace App\Http\Controllers;

use App\City;
use App\Http\Requests\MpUser\MpUserStore;
use App\Http\Requests\VendorUser\VendorUserStore;
use App\Http\Requests\VendorUser\VendorUserUpdate;
use App\MarketplaceUser;
use App\Notifications\RetailerUserCreate;
use App\SuperUser;
use App\UserRole;
use App\State;
use App\User;
use App\Vendor;
use App\VendorUser;
use App\Warehouse;
use App\Http\Requests\MpUser\MpUserUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use DataTables;


class UserController extends Controller
{


    public function index()
    {
        $users = Auth::user()->latest()->paginate(10);

        return view('users.'.getActiveGuard().'.index', compact('users'));
    }


    public function userProfile($id)
    {
        $user = Auth::user()->findOrFail($id);
        return view('commonView.profile', compact('user'));
    }


    public function userProfileUpdate(VendorUserUpdate $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'mobile' => 'required|max:15',
            'pass' => 'nullable',
        ]);

        $user = Auth::user()->findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        $MakeHashNewPassword = Hash::make($request->pass);
        if ($request->pass)
        {
            if (Hash::check($request->pass, $user->password)){
                $user->save();
                return redirect()->back()->with('success', 'Updated your Profile !');
            }else{
                $user->password = $MakeHashNewPassword;
                $user->save();
                return redirect()->back()->with('success', 'Updated your Profile with password !');
            }
        }
        $user->save();
        return redirect()->back()->with('success', 'Updated your Profile !');
    }


    public function userDynamicValue(Request $request)
    {

        if (!$request->ajax())
        {
            abort(404);
        }

        if($request->type == 'state'){
            $getStateOrDivision = State::where('country_id', $request->id)->get();
            $output = "<option selected value=''>Select Sate</option>";
            foreach ($getStateOrDivision as $sate)
            {
                $output .= "<option value='$sate->id'>$sate->name</option>";
            }
            return response()->json(['state' => $output]);
        }elseif($request->type == 'city'){
            $getCity = City::where('state_id', $request->id)->get();
            $output = "<option selected value=''>Select City</option>";
            foreach ($getCity as $city)
            {
                $output .= "<option value='$city->id'>$city->name</option>";
            }
            return response()->json(['city' => $output]);
        }

    }



    public function userList()
    {
        $title = 'Vendor Users';
        $page_detail = 'List of Vendor Users';
        return view('users.'.getActiveGuard().'.index', compact('title', 'page_detail'));
    }
    public function mpUserList()
    {
        $title = 'Marketplace Users';
        $page_detail = 'List of Marketplace Users';
        return view('mp_users.'.getActiveGuard().'.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $users = Auth::user()->with('user_role','warehouse')->where(['vendor_id' => auth()->user()->vendor_id])
            ->get();
        foreach ($users as $user){
            $user->details = Str::limit($user->details,50);
        }

        return Datatables::of($users)
            ->addIndexColumn()

            ->editColumn('status', function ($user) {
                if ($user->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($user) {
                if ($user->image) return '
                        <div class="pop_img" data-img="'.asset($user->image).'">
                            <img width="50"
                                 src="'.asset($user->image).'"
                                 alt="image">
                        </div>
                ';
                return '
                        <div>
                            No Image
                        </div>
                ';
            })
            ->addColumn('action', function ($user) {
                if(auth()->id() == $user->id) return '<div class="btn-group">
                        <a href="/admin/user/' . $user->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        </div>
                        ';
                   return '<div class="btn-group">
                        <a href="/admin/user/' . $user->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteUser('.$user->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteUserForm-'.$user->id.'" action="/admin/user/'. $user->id .'/delete">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';

            })
            ->rawColumns(['status','image','action'])
            ->make(true);

    }

    public function getMpListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $users = MarketplaceUser::all();
        foreach ($users as $user){
            $user->details = Str::limit($user->details,50);
        }

        return Datatables::of($users)
            ->addIndexColumn()

            ->editColumn('status', function ($user) {
                if ($user->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($user) {
                if ($user->image) return '
                        <div class="pop_img" data-img="'.asset($user->image).'">
                            <img width="50"
                                 src="'.asset($user->image).'"
                                 alt="image">
                        </div>
                ';
                return '
                        <div>
                            No Image
                        </div>
                ';
            })
            ->addColumn('action', function ($user) {
                if(auth()->id() == $user->id) return '<div class="btn-group">
                        <a href="/admin/mp_user/' . $user->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        </div>
                        ';
                   return '<div class="btn-group">
                        <a href="/admin/mp_user/' . $user->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteUser('.$user->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteUserForm-'.$user->id.'" action="/admin/mp_user/'. $user->id .'/delete">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';

            })
            ->rawColumns(['status','image','action'])
            ->make(true);

    }


    public function addUserDynamic(Request $request)
    {

        if (!$request->ajax())
        {
            abort(404);
        }

        $id = $request->id;
        $findWarehouse = Warehouse::find($id);
        if ($findWarehouse)
        {
            $search_items = Warehouse::where('vendor_id', Auth::user()->vendor_id)
                ->where('name', '=', $findWarehouse->name)->get();
            $output = '<option value="">Select Type</option>';

            foreach ($search_items as $item)
            {
                $output .= '<option value="'.$item->type.'">'.$item->type.'</option>';
            }
            return $output;
        }else{
            return response()->json('false');
        }
    }

    public function addUserForm()
    {
        $title = 'Create Vendor User';
        $page_detail = 'Create a User for your Vendor';
        $roles = UserRole::where('user_type_id', Auth::user()->user_type_id)->get();
        $warehouses = DB::table('warehouses')->where('vendor_id', Auth::user()->vendor_id)->get()->unique('name');
        return view('users.'.getActiveGuard().'.create', compact('roles', 'warehouses','title', 'page_detail'));
    }
    public function addMpUserForm()
    {
        $title = 'Create Retail User';
        $page_detail = 'Create a Retail User';
        $roles = UserRole::where('user_type_id', Auth::user()->user_type_id)->get();
        $warehouses = DB::table('warehouses')->where('vendor_id', Auth::user()->vendor_id)->get()->unique('name');
        return view('mp_users.'.getActiveGuard().'.create', compact('roles', 'warehouses','title', 'page_detail'));
    }

    public function addUserStore(VendorUserStore $request)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->email) {
                $email = SuperUser::where(['email' => $request->email])->value('email');
                if ($email){
                    return redirect()->back()->with('error', 'Duplicate email found please insert another email!');
                }
            }

            $user_role = UserRole::find($request->user_role_id);

            if (strtolower($user_role->name) == 'admin'){
                $warehouse_id = null;
                $warehouse_type_name =  null;
            }else{
              $warehouse_id =  $request->warehouse_id;
              $warehouse_type_name =  $request->warehouse_type_name;
            }

            $request['vendor_id'] = auth()->user()->vendor_id;

            $userData['name'] = $request->name;
            $userData['email'] = $request->email;
            $userData['password'] = Hash::make($request->password);
            $userData['mobile'] = $request->mobile;
            $userData['user_type_id'] = getActiveUserTypeId();
            $userData['user_role_id'] = intval($request->user_role_id);
            $userData['vendor_id'] = $request->vendor_id;
            //$userData['image'] = $request->image;
            $userData['gender'] = $request->gender;
            $userData['details'] = $request->details;
            $userData['status'] = $request->status ?? 0;
            $userData['warehouse_id'] = $warehouse_id;
            $userData['warehouse_type_name'] = $warehouse_type_name;
            $userData['created_by'] = auth()->user()->id;
            $user = Auth::user()->create($userData);

            if(!empty($request->image)) {

                $path_info = getPathInfo(['usersprofiles', 'images', 'original',$user->id]);
                $thumbnail_path_info = getPathInfo(['usersprofiles', 'images', 'thumbnail',$user->id]);
                makeDirectory($path_info,0755);
                makeDirectory($thumbnail_path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $user->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;
                //$setName = $user->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

                $original_imageLocation = 'backend/uploads/usersprofiles/images/original/'.$user->id.'/'.$setName;
                Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;


                // for thumbnail image

                $thumbnail_imageLocation = 'backend/uploads/usersprofiles/images/thumbnail/' . $user->id . '/' . $setName;
                Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
                $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;


                $user->image = $original_imageLocation;
                $user->image_url = $image_url;
                $user->thumbnail_image = $thumbnail_imageLocation;
                $user->thumbnail_image_url = $thumbnail_image_url;
                $user->save();
            }
        } catch (Exception $exc) {
            // Rollback Transaction
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }
        // Commit Transaction
        DB::commit();
        return redirect()->back()->with('success', 'User created success !');
    }


    public function addMpUserStore(MpUserStore $request)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->email) {
                $email = SuperUser::where(['email' => $request->email])->value('email');
                if ($email){
                    return redirect()->back()->with('error', 'Duplicate email found please insert another email!');
                }
            }


//            $request['vendor_id'] = auth()->user()->vendor_id;

            $userData['name'] = $request->name;
            $userData['email'] = $request->email;
            $userData['password'] = Hash::make($request->password);
            $userData['mobile'] = $request->mobile;
            $userData['user_type_id'] = 3;
            $userData['image'] = $request->image;
            $userData['gender'] = $request->gender;
            $userData['details'] = $request->details;
//            $userData['date_of_birth'] = $request->date_of_birth;
            $userData['status'] = $request->status ?? 0;
            $userData['created_by'] = auth()->user()->id;
            $user = MarketplaceUser::create($userData);

            $image = $request->file('img');
            if ($image){
                $path_info = getPathInfo(['markeplace_customers', 'images', 'original',$user->id]);
                makeDirectory($path_info,0755);
                $setName = $user->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

                $original_imageLocation = 'backend/uploads/markeplace_customers/images/original/'.$user->id.'/'.$setName;
                Image::make($image)->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;

                $user->image = $original_imageLocation;
                $user->image_url = $image_url;
                $user->save();
            }
            if (!is_null($user->email)){
                $user->notify(new RetailerUserCreate($user,$request->password));
            }
        } catch (Exception $exc) {
            // Rollback Transaction
            DB::rollback();
            return redirect()->back()->with('error', $exc->getMessage());
        }
        // Commit Transaction
        DB::commit();
        return redirect()->back()->with('success', 'Marketplace User created success !');
    }



    public function addUserEdit($user)
    {
        $title = 'Edit Vendor User';
        $page_detail = 'Edit a User for your Vendor';
        $warehouses = DB::table('warehouses')->where('vendor_id', Auth::user()->vendor_id)->get()->unique('name');
        $user = auth()->user()->find($user);
        if ($user->vendor_id == auth()->user()->vendor_id)
        {
            $roles = UserRole::where('user_type_id', Auth::user()->user_type_id)->get();
            return view('users.'.getActiveGuard().'.edit', compact('user', 'roles','warehouses','title', 'page_detail'));
        }else {
            abort(404);
        }

    }

    public function addMpUserEdit($user)
    {
        $title = 'Edit Retail User';
        $page_detail = 'Edit a Retail User';
        $user = MarketplaceUser::find($user);
//        if ($user->vendor_id == auth()->user()->vendor_id)
        if ($user)
        {
//            $roles = UserRole::where('user_type_id', Auth::user()->user_type_id)->get();
            return view('mp_users.'.getActiveGuard().'.edit', compact('user', 'title', 'page_detail'));
        }else {
            abort(404);
        }

    }

    public function addUserupdate(VendorUserUpdate $request, VendorUser $user)
    {
//dd($request->all());
        if($request->email) {
            $email = SuperUser::where(['email' => $request->email])->where('id', '<>' , $user->id)->value('email');
            if ($email){
                return redirect()->back()->with('error', 'Duplicate email found please insert another email!');
            }
        }

        $user_role = UserRole::find($request->user_role_id);

        if (strtolower($user_role->name) == 'admin'){
            $warehouse_id = null;
            $warehouse_type_name =  null;
        }else{
            $warehouse_id =  $request->warehouse_id;
            $warehouse_type_name =  $request->warehouse_type_name;
        }

        $request['status'] = $request->status ?? '0';
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'user_type_id' => getActiveUserTypeId(),
            'user_role_id' => intval($request->user_role_id),
            //'vendor_id' => $request->vendor_id,
            //'image' => $request->image,
            'gender' => $request->gender,
            'details' => $request->details,
            'status' => $request->status ?? 0,
            'warehouse_id' => $warehouse_id,
            'warehouse_type_name' => $warehouse_type_name,
            'updated_by' => auth()->user()->id,
        ]);

        if(!empty($request->image)) {

            $path_info = getPathInfo(['usersprofiles', 'images', 'original',$user->id]);
            $thumbnail_path_info = getPathInfo(['usersprofiles', 'images', 'thumbnail',$user->id]);
            makeDirectory($path_info,0755);
            makeDirectory($thumbnail_path_info,0755);

            $img = $request->image;
            $img = json_decode($img, true);
            $img_type = explode('/', $img['type']);
            $extension = $img_type[1];
            $setName = $user->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;
            //$setName = $user->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

            $original_imageLocation = 'backend/uploads/usersprofiles/images/original/'.$user->id.'/'.$setName;
            Image::make($img['data'])->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;


            // for thumbnail image

            $thumbnail_imageLocation = 'backend/uploads/usersprofiles/images/thumbnail/' . $user->id . '/' . $setName;
            Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info.'/'.$setName, 100);
            $thumbnail_image_url = URL::to('/') . '/'  . $thumbnail_imageLocation;


            $user->image = $original_imageLocation;
            $user->image_url = $image_url;
            $user->thumbnail_image = $thumbnail_imageLocation;
            $user->thumbnail_image_url = $thumbnail_image_url;
            $user->save();
        }
        return redirect()->route('admin.user')->with('success', 'User updated success !');
    }

    public function addMpUserupdate(MpUserUpdate $request, $user)
    {

        if($request->email) {
            $email = SuperUser::where(['email' => $request->email])->where('id', '<>' , $user)->value('email');
            if ($email){
                return redirect()->back()->with('error', 'Duplicate email found please insert another email!');
            }
        }
        $user = MarketplaceUser::where('id',$user)->first();
        $request['status'] = $request->status ?? '0';
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'gender' => $request->gender,
            'details' => $request->details,
            'status' => $request->status,
            'updated_by' => auth()->user()->id,
        ]);
        $image = $request->file('img');
        if ($image){
            $path_info = getPathInfo(['markeplace_customers', 'images', 'original',$user->id]);
            makeDirectory($path_info,0755);
            $setName = $user->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

            $original_imageLocation = 'backend/uploads/markeplace_customers/images/original/'.$user->id.'/'.$setName;
            Image::make($image)->save($path_info.'/'.$setName, 100);
            $image_url = URL::to('/'). '/' . $original_imageLocation;

            $user->image = $original_imageLocation;
            $user->image_url = $image_url;
            $user->save();
        }
        return redirect()->route('admin.mp_user')->with('success', 'Marketplace User updated success !');
    }


    public function addUserDelete($user)
    {
        $user = auth()->user()->find($user);
        if (auth()->user()->user_role->name != 'Admin')
        {
            return redirect()->back()->with('warning', 'you are not allow to delete this user');
        }

        if ($user->vendor_id == Auth::user()->vendor_id)
        {
            if ($user->image != null)
            {
                if (file_exists($user->image)){
                    unlink($user->image);
                }
            }
            $user->delete();
            return redirect()->back()->with('success', 'User deleted success');
        }else{
            abort(404);
        }
    }

    public function addMpUserDelete($user)
    {
        $mp_user = MarketplaceUser::find($user);

        if (!empty($mp_user))
        {
            if ($mp_user->image != null)
            {
                if (file_exists($mp_user->image)){
                    unlink($mp_user->image);
                }
            }
            $mp_user->delete();
            return redirect()->back()->with('success', 'Retail User deleted success');
        }else{
            abort(404);
        }
    }
    public function getWarehouseType($id){
        $warehouse = DB::table('warehouses')->where('id', $id)->value('type');
        return $warehouse;
    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $user = auth()->user()->where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
        if ($user) {
            $user->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }


    public function mpActiveUnactive(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $user = MarketplaceUser::where('id', $request->id)->first();
        if ($user) {
            $user->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }




}
