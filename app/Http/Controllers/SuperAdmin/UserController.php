<?php

namespace App\Http\Controllers\SuperAdmin;

use App\City;
use App\Http\Controllers\Controller;
use App\State;
use App\User;
use App\SuperUser;
use App\VendorUser;
use App\UserRole;
use App\Http\Requests\VendorUser\VendorUserStore;
use App\Http\Requests\VendorUser\VendorUserUpdate;
use App\Http\Requests\SuperUser\SuperUserStore;
use App\Http\Requests\SuperUser\SuperUserUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use DataTables;
class UserController extends Controller
{
    public function index()
    {
        $users = auth()->user()->latest()->where('user_type_id', 1)->get();
        return view('users.'.getActiveGuard().'.index', compact('users'));
    }
    public function vendorUser()
    {
        $users = VendorUser::latest()->where('user_type_id', 2)->get();
        $title = 'Vendor User';
        $page_detail = 'List of Vendor Users';
        return view('users.'.getActiveGuard().'.vendor_user', compact('users','title', 'page_detail'));
    }

    public function getVendorUserListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $users = VendorUser::with('stateOrDivision','city','country','vendor')->latest()->where('user_type_id', 2)->get();

        return DataTables::of($users)
            ->addIndexColumn()

            ->editColumn('status', function ($user) {
                if ($user->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($user) {
                return '
                        <div class="pop_img" data-img="'.asset($user->image).'">
                            <img width="50"
                                 src="'.asset($user->image).'"
                                 alt="image">
                        </div>
                ';
            })
            ->rawColumns(['status','image'])
            ->make(true);

    }

    public function vendorUserCreate()
    {
        $title = 'Create Vendor User';
        $page_detail = 'Create a Vendor User';
        return view('users.'.getActiveGuard().'.vendor_user_create', compact('title', 'page_detail'));
    }
    public function superUser()
    {
        $users = SuperUser::latest()->where('user_type_id', 1)->get();
        $title = 'Super User';
        $page_detail = 'List of Super Users';
        return view('users.'.getActiveGuard().'.super_user', compact('users','title', 'page_detail'));
    }

    public function getSuperUserListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $users = SuperUser::with('stateOrDivision','city','country')->latest()->where('user_type_id', 1)->get();

        return DataTables::of($users)
            ->addIndexColumn()

            ->editColumn('status', function ($user) {
                if ($user->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$user->status.'" data_id="'.$user->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($user) {
                return '
                        <div class="pop_img" data-img="'.asset($user->image).'">
                            <img width="50"
                                 src="'.asset($user->image).'"
                                 alt="image">
                        </div>
                ';
            })
            ->rawColumns(['status','image'])
            ->make(true);

    }
    public function superUserCreate()
    {
        $title = 'Create Super User';
        $page_detail = 'Create a Super User';
        return view('users.'.getActiveGuard().'.super_user_create', compact('title', 'page_detail'));
    }

    public function userProfile($id)
    {
        $user = User::findOrFail($id);
        return view('users.'.getActiveGuard().'.profile', compact('user'));
    }


    public function userProfileUpdate(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,'.$id,
            'mobile' => 'required|max:15',
            'pass' => 'nullable',
        ]);
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        $MakeHashNewPassword = Hash::make($request->pass);
        if ($request->pass)
        {
            if (Hash::check($request->pass, $user->password)){
                $user->save();
                return redirect()->action('SuperAdmin\UserController@index')->with('success', 'Updated your Profile !');
            }else{
                $user->password = $MakeHashNewPassword;
                $user->save();
                return redirect()->action('SuperAdmin\UserController@index')->with('success', 'Updated your Profile with password !');
            }
        }
        $user->save();
        return redirect()->action('SuperAdmin\UserController@index')->with('success', 'Updated your Profile !');
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

    public function addVendorUserStore(VendorUserStore $request)
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
            $userData['name'] = $request->name;
            $userData['email'] = $request->email;
            $userData['password'] = Hash::make($request->password);
            $userData['mobile'] = $request->mobile;
            $userData['user_type_id'] = 2;
            $userData['user_role_id'] = UserRole::where('name','Admin')->where('user_type_id',2)->value('id');
            $userData['country_id'] = $request->country_id;
            $userData['state_id'] = $request->state_id;
            $userData['city_id'] = $request->city_id;
            $userData['post_code'] = $request->post_code;
            $userData['vendor_id'] = $request->vendor_id;
            $userData['date_of_birth'] = $request->date_of_birth;
            $userData['gender'] = $request->gender;
            $userData['details'] = $request->details;
            $userData['status'] = $request->status;
//            $userData['warehouse_id'] = $request->warehouse_id;
//            $userData['warehouse_type_name'] = $request->warehouse_type_name;
            $userData['created_by'] = auth()->user()->id;
            $user = VendorUser::create($userData);



            if(!empty($request->image)) {
                $path_info = getPathInfo(['usersprofiles',$user->id]);
                makeDirectory($path_info,0755);
                //$setName = $user->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $user->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                $imageLocation = ('backend/uploads/usersprofiles/'.$user->id.'/'.$setName);
                Image::make($img['data'])->save($path_info.'/'.$setName,100);
                $user->image = $imageLocation;
                $user->save();
            }

            // Commit Transaction
            DB::commit();
            return redirect()->action('SuperAdmin\UserController@vendorUser')->with('success', 'Vendor User created success!');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    public function addSuperUserStore(SuperUserStore $request)
    {
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if($request->email) {
                $email = VendorUser::where(['email' => $request->email])->value('email');
                if ($email){
                    return redirect()->back()->with('error', 'Duplicate email found please insert another email!');
                }
            }

            $request['status'] = $request->status ?? 0;
            $userData['name'] = $request->name;
            $userData['email'] = $request->email;
            $userData['password'] = Hash::make($request->password);
            $userData['mobile'] = $request->mobile;
            $userData['user_type_id'] = 1;
            $userData['user_role_id'] = intval($request->user_role_id);
            $userData['country_id'] = $request->country_id;
            $userData['state_id'] = $request->state_id;
            $userData['city_id'] = $request->city_id;
            $userData['post_code'] = $request->post_code;
            $userData['image'] = $request->image;
            $userData['date_of_birth'] = $request->date_of_birth;
            $userData['image'] = $request->image;
            $userData['gender'] = $request->gender;
            $userData['details'] = $request->details;
            $userData['status'] = $request->status;
            $userData['created_by'] = auth()->user()->id;
            $userData['updated_by'] = auth()->user()->id;
            $user = SuperUser::create($userData);

            if(!empty($request->image)) {
                $path_info = getPathInfo(['usersprofiles',$user->id]);
                makeDirectory($path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $user->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;
                //$setName = $user->name . '-' . rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

                $imageLocation = ('backend/uploads/usersprofiles/'.$user->id.'/'.$setName);
                Image::make($img['data'])->save($path_info.'/'.$setName,100);
                $user->image = $imageLocation;
                $user->save();
            }

            // Commit Transaction
            DB::commit();


            return redirect()->back()->with('success', 'Super User created success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }



    }

    public function activeUnactiveVendor(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $user = VendorUser::where('id', $request->id)->first();
        if ($user) {
            $user->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }
    public function activeUnactive(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $user = auth()->user()->where('id', $request->id)->first();
        if ($user) {
            $user->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }
}
