<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index()
    {
        return view('orders.index');
    }


    public function create()
    {
        return view('orders.create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show($id)
    {
        return view('orders.view');
    }


    public function edit($id)
    {
       //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
       //
    }
}
