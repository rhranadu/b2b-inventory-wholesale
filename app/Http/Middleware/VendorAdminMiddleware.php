<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VendorAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->user_type_id == 2 && Auth::user()->user_role->name == 'Admin')
        {
            return $next($request);
        }else{
            return redirect()->back()->with('error', 'You are not Authenticate');
        }
    }
}
