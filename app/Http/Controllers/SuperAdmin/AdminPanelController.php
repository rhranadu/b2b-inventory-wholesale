<?php

namespace App\Http\Controllers\SuperAdmin;

use App\AdminPanel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPanelController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        if ($request->name)
        {
            $admin_panel = AdminPanel::where('user_id', Auth::user()->id)->first();
            $admin_panel->panel_name = $request->name;
            $admin_panel->user_id = Auth::user()->id;
            $admin_panel->save();
            return response()->json('true');
        }else{
            return response()->json('false');
        }


    }


    public function show(AdminPanel $adminPanel)
    {
        //
    }


    public function edit(AdminPanel $adminPanel)
    {
        //
    }


    public function update(Request $request, AdminPanel $adminPanel)
    {
        //
    }


    public function destroy(AdminPanel $adminPanel)
    {
        //
    }
}
