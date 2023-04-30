<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class OperatorMiddleware
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
            $operator_role = in_array('Operator', $user->user_role->pluck('name')->toArray());
            if ($operator_role)
            {
                return $next($request);
            }else{
                return redirect()->back()->with('error', 'You are not Authenticate');
            }
        }
    }
}
