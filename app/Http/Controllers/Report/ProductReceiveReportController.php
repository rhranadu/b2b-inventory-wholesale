<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\ProductReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReceiveReportController extends Controller
{
    public function index()
    {
        $returns = ProductReturn::where('vendor_id', Auth::user()->vendor_id)->get();
        return view('reports.product_returns.index', compact('returns'));
    }
}
