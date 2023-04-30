<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function index()
    {
        $title = 'Super Admin Dashboard';
        $page_detail = 'Super Admin Dashboard Details';
        return view('super_admin.dashboard.dashboard',compact('title', 'page_detail'));
    }

}
