<?php

namespace App\Http\Controllers\Report;

use App\PosCustomer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PosCustomersReportController extends Controller
{


    public function posCustomerReports()
    {
        $poscustomers = PosCustomer::orderBy('id', 'DESC')->where('vendor_id', Auth::user()->vendor_id)->get();
        return view('reports.pos_customers.index', compact('poscustomers'));
    }


    public function showPosCustomerSale(PosCustomer $customer)
    {
        if ($poscustomer->vendor_id == Auth::user()->vendor_id)
        {
            return view('reports.pos_customers.sale', compact('poscustomer'));
        }else{
            abort(404);
        }

    }

}
