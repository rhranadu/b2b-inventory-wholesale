<?php

namespace App\Http\Controllers;

use App\PosCustomer;
use App\ProductPoolStockDetail;
use App\PurchaseDetail;
use App\Vendor;
use App\VendorExpense;
use App\Product;
use App\ProductStock;
use App\Purchase;
use App\Sale;
use App\SalePayment;
use App\StockDetail;
use App\Supplier;
use App\VendorCommissionTransaction;
use App\SupplierPaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        $title = 'Vendor Dashboard';
        $page_detail = 'Vendor Dashboard Details';

        if (Auth::check())
        {
            if (Auth::user()->user_type_id == 2)
            {
                if (isset(Auth::user()->user_role->name) && strtolower(Auth::user()->user_role->name) == 'pos') {
                    return redirect('/pos');
                }
                $today_sale_invoice = Sale::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('today')))
                    ->where('vendor_id', Auth::user()->vendor_id)
                    ->distinct()
                    ->where('vendor_id', Auth::user()->vendor_id)
                    ->count('id');
                $today_purchase_invoice = Purchase::where('created_at', '>=', date('Y-m-d H:i:s', strtotime('today')))
                    ->where('vendor_id', Auth::user()->vendor_id)
                    ->distinct()
                    ->where('vendor_id', Auth::user()->vendor_id)
                    ->count('id');
                $total_products = Product::distinct()->where('vendor_id', Auth::user()->vendor_id)->count('id');
                $total_products_items = StockDetail::where('vendor_id', Auth::user()->vendor_id)->sum('quantity');
                //$total_sale = SalePayment::where('vendor_id', Auth::user()->vendor_id)->groupBy('sale_id')->sum('final_total');
                $saleQuery = Sale::where('vendor_id', Auth::user()->vendor_id);

                $total_sale = $saleQuery->sum('final_total');
                $totalReceived = VendorCommissionTransaction::where('vendor_id', auth()->user()->vendor_id)->where('status', '!=', 'Submitted')->sum('amount');
                // $totalReceived = 0;
                // foreach($trans as $t){

                //     $totalReceived = $totalReceived + $t->amount;
                // }
                $totalCommission = $saleQuery->where( function ($q) {
                                    $q->orWhere('status','delivered');
                                    $q->orWhere('status','confirmed');
                                    $q->orWhere('status','processed');
                                    $q->orWhere('status','shipped');
                                })->sum('superadmin_will_get') - $totalReceived;

                $today_sale = Sale::where('vendor_id', Auth::user()->vendor_id)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('today')))->sum('final_total');

                $p_for_alert_products = Product::where('vendor_id', Auth::user()->vendor_id)->get();
                $alert_products =array();
                foreach ($p_for_alert_products as $p){
                    if ($p->alert_quantity > $p->available_quantity){
                        $alert_products[] = $p->id;
                    }
                }

                $data = [
                    'today_sale_invoice' => $today_sale_invoice,
                    'today_purchase_invoice' => $today_purchase_invoice,
                    'total_products' => $total_products,
                    'total_products_items' => $total_products_items,
                    'alert_products' => count($alert_products),
                    'total_sale' => $total_sale,
                    'today_sale' => $today_sale,
                    'payable_commission' => $totalCommission,
                ];
                $users_vendor = Vendor::select('name','logo')->where('id', Auth::user()->vendor_id)->first();

                $sales = Sale::orderBy('created_at', 'DESC')->where('vendor_id', Auth::user()->vendor_id)->get();
                $duesales = SalePayment::where('vendor_id', Auth::user()->vendor_id)->get();
                $products = Product::where('vendor_id', Auth::user()->vendor_id)->get();
                $purchases = Purchase::orderBy('created_at', 'DESC')->where('vendor_id', Auth::user()->vendor_id)->get();
                $storeProducts = StockDetail::where(['vendor_id' => Auth::user()->vendor_id])->get();
                $customers = PosCustomer::orderBy('created_at', 'DESC')->where('vendor_id', Auth::user()->vendor_id)->get();
                $vendorexpenses = VendorExpense::orderBy('created_at', 'DESC')->where('vendor_id', Auth::user()->vendor_id)->get();
                $suppliers =Supplier::orderBy('created_at', 'DESC')->where('vendor_id', Auth::user()->vendor_id)->get();
                return view('dashboard.dashboard',
                    compact('data','users_vendor','sales', 'products', 'purchases', 'storeProducts', 'duesales', 'customers', 'vendorexpenses', 'suppliers', 'title', 'page_detail'));
            }else{
                $data = [];
                $sales = [];
                $duesales = [];
                $products = [];
                $purchases = [];
                $storeProducts = [];
                $customers = [];
                $vendorexpenses = [];
                $suppliers = [];
                $totalsPaymentsToSuppliers = [];
                return view('dashboard.dashboard',
                    compact('data','sales',  'storeProducts', 'duesales', 'customers', 'vendorexpenses', 'purchases', 'products', 'suppliers', 'totalsPaymentsToSuppliers', 'title', 'page_detail'));
            }

        }else{
            return view('auth.login');
        }

    }

    private function saleRole()
    {
        $sale = false;
        $sale_role = in_array('Sale', Auth::user()->roles->pluck('name')->toArray());
        if(Auth::user()->warehouse_type_name === 'show-room' && $sale_role)
        {
            $sale = true;
        }else{
            $sale = false;
        }
        return $sale;
    }


}
