<?php

namespace App\Http\Middleware;

use App\MarketplaceUser;
use App\Providers\RouteServiceProvider;
use App\SuperUser;
use App\User;
use App\VendorUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetAuthGuardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {

        $guards = empty($guards) ? [null] : $guards;
        $credentials = $request->only('email','password');
        $user = false;
        foreach ($guards as $guard) {
            if ($guard == 'superadmin') {
                $user = SuperUser::where([['email' , $request->email],['status', 1]])->first();
            } elseif ($guard == 'vendor') {
                $user = VendorUser::where([['email' , $request->email],['status', 1]])->has('vendor')->with('vendor')->first();
                if(empty($user['vendor']['status'])){
                    return $next($request);
                }
            } elseif ($guard == 'marketplace'){
                $user = MarketplaceUser::where([['email' , $request->email],['status', 1]])->first();
            }
            if ($user && Auth::guard($guard)->attempt($credentials,$request->has('remember'))) {
                Auth::shouldUse($guard);
            }
        }
        return $next($request);
    }
}
