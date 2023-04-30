<?php

namespace App\Http\Controllers;

use App\PasswordReset;
use App\SuperUser;
use App\User;
use App\VendorUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class AccountsController extends Controller
{
    public function validatePasswordRequest (Request $request) {
        $vendorUser = VendorUser::where('email', '=', $request->email)
            ->first();
        $superUser = SuperUser::where('email', '=', $request->email)
            ->first();

        //Check if the user exists
        if (!$vendorUser && !$superUser) {
            return redirect()->back()->withErrors(['email' => trans('User does not exist')]);
        }
        //Create Password Reset Token

        if ($vendorUser){
            PasswordReset::create([
                'email' => $request->email,
                'user_id' => $vendorUser->id,
                'table_name' => 'App\VendorUser',
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ]);
        }else{
            PasswordReset::create([
                'email' => $request->email,
                'user_id' => $superUser->id,
                'table_name' => 'App\SuperUser',
                'token' => Str::random(60),
                'created_at' => Carbon::now()
            ]);
        }
        //Get the token just created above
        $tokenData = PasswordReset::where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return redirect()->back()->with('status', trans('A reset link has been sent to your email address.'));
        } else {
            return redirect()->back()->withErrors(['error' => trans('A Network Error occurred. Please try again.')]);
        }


    }

    private function sendResetEmail($email, $token)
    {

        //Retrieve the user from the database
        if ($email){
            $user = VendorUser::where('email', '=', $email)->select('id','name', 'email')
                ->first();
            if (!$user){
                $user = SuperUser::where('email', '=', $email)->select('id','name', 'email')
                    ->first();
            }
        }
        //Generate, the password reset link. The token generated is embedded in the link
        $link = URL::to('/') . '/' . 'password/reset/' . $token . '?email=' . urlencode($user->email);

        try {
            //Here send the link with CURL with an external email API
            Mail::send('super_admin.email_templates.emails.forget_password', ['link'=>$link], function ($message) use ($user) {
                $message->subject('Password Reset Link');
                $message->to($user->email);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


    public function resetPassword(Request $request)
    {

        if ($request->email){
            $user = VendorUser::where('email', '=', $request->email)
                ->value('email');
            if (!$user){
            $user = SuperUser::where('email', '=', $request->email)
                ->value('email');
            }
        }

        //Validate input
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'token' => 'required' ]);
        //check if payload is valid before moving on
        if (!$user && $validator->fails()) {
            return redirect()->back()->withErrors(['email' => 'Please complete the form']);
        }

        $password = $request->password;
        // Validate the token
        $tokenData = PasswordReset::where('token', $request->token)->first();
        // Redirect the user back to the password reset request form if the token is invalid
        if (!$tokenData) return view('auth.passwords.email');

            if ($tokenData->email){
                    $vendorUser = VendorUser::where('email', '=', $tokenData->email)
                        ->first();
                if (!$vendorUser){
                    $superUser = SuperUser::where('email', '=', $tokenData->email)
                        ->first();
                }
            }

            // Redirect the user back if the email is invalid
            if (!$vendorUser && !$superUser) return redirect()->back()->withErrors(['email' => 'Email not found']);

            if ($vendorUser){
            //Hash and update the new password
                $vendorUser->password = Hash::make($password);
                $vendorUser->save(); //or $user->save();
            //login the user immediately they change password successfully
            Auth::login($vendorUser);

            //Delete the token
                PasswordReset::where('email', $vendorUser->email)
                ->delete();
            }else{
                $superUser->password = Hash::make($password);
                $superUser->save(); //or $user->save();
                Auth::login($superUser);

                //Delete the token
                PasswordReset::where('email', $superUser->email)
                    ->delete();
            }
            //Send Email Reset Success Email
            if ($this->sendSuccessEmail($tokenData->email)) {
                if ($vendorUser) {
                    return redirect()->action('SuperAdmin\DashboardController@index');
                }else {
                    return redirect()->action('DashboardController@index');
                }
    //            return view('dashboard.dashboard');
            } else {
                return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
            }

    }

    private function sendSuccessEmail($email)
    {

        //Retrieve the user from the database
        if ($email){
            $user = VendorUser::where('email', '=', $email)->select('id','name', 'email')
                ->first();
            if (!$user){
                $user = SuperUser::where('email', '=', $email)->select('id','name', 'email')
                    ->first();
            }
        }
        $link = URL::to('/');
        try {
            //Here send the link with CURL with an external email API
            Mail::send('super_admin.email_templates.emails.reset_password', ['user'=>$user,'link'=>$link], function ($message) use ($user) {
                $message->subject('Reset Password Successful');
                $message->to($user->email);
            });
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
