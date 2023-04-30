<?php

namespace App\Http\Controllers\Auth;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\State;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Image;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('superadmin');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'mobile' => ['required', 'string', 'min:8', 'max:15'],
            'country_id' => ['required', 'numeric'],
            'state_id' => ['required', 'numeric'],
            'city_id' => ['required', 'numeric'],
            'post_code' => ['required', 'numeric'],
            'gender' => ['required', 'string'],
            'img' => ['nullable', 'mimes:jpeg,bmp,png'],
            'status' => ['nullable', 'max:1'],
            'vendor_id' => ['nullable'],
        ]);
    }


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {

        $this->validator($request->all())->validate();
        $image = $request->file('img');

        if ($image){

            $setImageName = rand() . '-' . uniqid() . '.' . $image->getClientOriginalExtension();

            $imagePath = ('backend/usersprofiles/'.$setImageName);

            Image::make($image)->resize(300,300)->save(public_path('backend/usersprofiles/'.$setImageName),100);

            $request['image'] = $imagePath;

        }else{

            $request['image'] = '';
        }

        $request['status'] = $request->status == 1 ?? '0';

        event(new Registered($user = $this->create($request->except('_token'))));

        return redirect()->route('superadmin.user.register')->with('success', 'New User created');

    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'mobile' => $data['mobile'],
            'country_id' => $data['country_id'],
            'state_id' => $data['state_id'],
            'city_id' => $data['city_id'],
            'post_code' => $data['post_code'],
            'user_type_id' => '2',
            'vendor_id' => $data['vendor_id'],
            'date_of_birth' => $data['date_of_birth'],
            'image' => $data['image'],
            'gender' => $data['gender'],
            'status' =>$data['status'],
        ]);

    }
}
