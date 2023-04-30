<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        foreach ($guards as $guard) {
            if ($guard == 'superadmin' && Auth::guard($guard)->check()) {
                return redirect()->route('superadmin.dashboard');
            } elseif ($guard == 'vendor' && Auth::guard($guard)->check()) {
                return redirect()->route('admin.dashboard');
            } elseif ($guard == 'marketplace' && Auth::guard('marketplace')->check()){
                return redirect()->route('admin.dashboard');
            }
        }
        
        return $next($request);
    }
}
