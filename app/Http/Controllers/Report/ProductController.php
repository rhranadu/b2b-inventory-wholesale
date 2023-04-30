<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('productVendor', 'productBrand','productCategory', 'productStockes')
            ->where('vendor_id', Auth::user()->vendor_id)
            ->paginate(10);
        return view('reports.products.index', compact('products'));
    }

    public function productDetail(Product $product)
    {
        if (Auth::user()->vendor_id === $product->vendor_id)
        {
            return view('reports.products.in_details', compact('product'));
        }else{
            abort(403);
        }
    }

}
