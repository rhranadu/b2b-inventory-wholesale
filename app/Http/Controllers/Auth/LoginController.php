<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\SubscriptionChecker\SubscriptionChecker;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;


    protected $redirectTo;
    public function redirectTo()
    {
        switch (getActiveGuard()) {
            case 'vendor':
                $this->redirectTo = route('admin.dashboard'); // who can get only role
                return $this->redirectTo;
                break;
            case 'superadmin':
                $this->redirectTo = route('superadmin.dashboard'); // who are a owner
                return $this->redirectTo;
                break;
            default:
                $this->redirectTo = '/';
                return $this->redirectTo;
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function  __construct()
    {
        $this->middleware('SetAuthGuard:superadmin,vendor,marketplace')->except('logout');
        $this->middleware('guest:superadmin,vendor,marketplace')->except('logout');
        // subscription checker
//          $this->middleware(function ($request, $next) {
//              $action = $request->route()->getAction();
//              $controller = class_basename($action['controller']);
//              list($controller, $action) = explode('@', $controller);

//              if ($action == 'login') {
// //                 return (new SubscriptionChecker())->SubscriptionCheck($request, $next);
//              }

//              return $next($request);
//          });
    }
}
