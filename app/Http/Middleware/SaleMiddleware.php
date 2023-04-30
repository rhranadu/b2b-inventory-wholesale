<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SaleMiddleware
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
        $user = Auth::user();
        if (Auth::check() && Auth::user()->user_type_id == 2) //it is only for vendor admin
        {
          return $next($request);
        }elseif (Auth::check() && Auth::user()->user_type_id == 0) // if vendor has a use, check there role
        {
           $sale_role =  in_array('Sale', $user->user_role->pluck('name')->toArray());
           if ($sale_role)
           {
               if (Auth::check() && $user->warehouse_type_name === 'show-room')
               {
                   return $next($request);
               }else{
                   return redirect()->back()->with('error', 'You are not Authenticate');
               }
           }else{
               return redirect()->back()->with('error', 'You are not Authenticate');
           }

        }
    }
}
