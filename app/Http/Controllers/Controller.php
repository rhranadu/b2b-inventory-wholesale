<?php

namespace App\Http\Controllers;

use App\PurchaseDetail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $current_warehouse_id;

    public function __construct()
    {
        $this->middleware(function($request, $next) {
            if (isset(auth()->user()->warehouse_id)){
            $this->current_warehouse_id = Auth::user()->warehouse_id;
            if (isset(auth()->user()->user_role->name)){
                if (strtolower(auth()->user()->user_role->name) != 'pos') {
                    if ($request->has('current_warehouse_id') && $request->current_warehouse_id) {
                        session(['current_warehouse_id' => $request->current_warehouse_id]);
                        $this->current_warehouse_id = $request->current_warehouse_id;
                    } elseif (session('current_warehouse_id')){
                        $this->current_warehouse_id = session('current_warehouse_id');
                    }
                }
            }
            }
            return $next($request);
        });

    }

    public function vendor()
    {
        $ll = where('vendor_id', auth()->user()->vendor_id);
        return $ll;
    }


}
