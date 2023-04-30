<?php

namespace App\Http\ViewComposers;

use App\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Route;
use DB;

class AdminLayout
{
    public function __construct(Request $request)
    {

    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {

        $users_vendor = Vendor::select('name','logo')->where('id', Auth::user()->vendor_id)->first();

        $view->with([
            'users_vendor' => $users_vendor,
        ]);
    }
}
