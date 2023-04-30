<?php

namespace App\Http\Middleware;

use App\AdminConfig;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminPanelMiddleware
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
        // $admin_panel = AdminConfig::where('name', 'admin_panel')->first();
        // if ($admin_panel)
        // {
        //     Session::put('template_name', $admin_panel->value);
        // }
        // $getFrom_session = Session::get('template_name');
        // if ($getFrom_session === 'Notika')
        // {
        //     Session::put(['breadcomb_container' => 'container', 'layouts' => 'layouts.crud-master']);
        // }elseif ($getFrom_session === 'Gentelella')
        // {
        //     Session::put(['breadcomb_container' => 'container', 'layouts' => 'layouts.gentelella.master']);
        // }elseif ($getFrom_session === 'AdminLTE')
        // {
        //     Session::put(['breadcomb_container' => 'pt-2', 'layouts' => 'layouts.admin_lte.master']);
        // }
        return $next($request);
    }
}
