<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Http\Requests\ProductAttributeMap\ProductAttributeMapStore;
use App\Http\Requests\ProductAttributeMap\ProductAttributeMapUpdate;
use App\ProductAttribute;
use App\ProductAttributeMap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use DataTables;
class ProductAttributeMapController extends Controller
{
    public function index()
    {
        $title = 'Product Attribute Map';
        $page_detail = 'List of product attribute map';
        return view('product_attribute_maps.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_attribute_maps = ProductAttributeMap::with('attributeName')
            ->where('vendor_id', auth()->user()->vendor_id)
            ->latest()
            ->get();

        return Datatables::of($product_attribute_maps)
            ->addIndexColumn()
            ->addColumn('action', function ($product_attribute_map) {
                return '<div class="btn-group">
                        <a href="/admin/product_attribute_map/' . $product_attribute_map->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteAttributeMap('.$product_attribute_map->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteAttributeMapForm-'.$product_attribute_map->id.'" action="/admin/product_attribute_map/'. $product_attribute_map->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['action'])
            ->make(true);

    }
    public function create()
    {
        $title = 'Create Product Attribute Map';
        $page_detail = 'Create a Product Attribute Map for your vendor';
        $productattributes = ProductAttribute::where('vendor_id', auth()->user()->vendor_id)->active()->get();
        return view('product_attribute_maps.create', compact('productattributes','title', 'page_detail'));
    }

    public function store(ProductAttributeMapStore $request)
    {
        foreach ($request->value as $singleVal){
            if($singleVal) {
                $attribute_value = ProductAttributeMap::where([
                    'vendor_id' => Auth::user()->vendor_id,
                    'product_attribute_id' => $request->product_attribute_id,
                    'value' => $singleVal
                ])->value('value');
                if ($attribute_value){
                    return redirect()->back()->with('error', 'Duplicate attribute value found please insert another value!');
                }
            }
            // check this attr map is already create for this vendor or not
            $checkAttr = ProductAttributeMap::where(['vendor_id' => auth()->user()->vendor_id, 'value' => strtolower($singleVal), 'product_attribute_id' => $request->product_attribute_id])->first();
            if ($checkAttr)
            {
                return redirect()->action('ProductAttributeMapController@index')->with('warning', ''.$singleVal.' Attribute Map Already Exists !');
            }

            ProductAttributeMap::create([
                'product_attribute_id' => $request->product_attribute_id,
                'vendor_id' => Auth::user()->vendor_id,
                'value' =>$singleVal,
                'created_by' => Auth::id(),
                'updated_by' =>Auth::id(),
            ]);

        }
        return redirect()->action('ProductAttributeMapController@index')->with('success', 'ProductAttributeMap Create Success !');
    }


    public function show (ProductAttributeMap $productAttributeMap)
    {

    }

    public function edit(ProductAttributeMap $productAttributeMap)
    {
        $title = 'Edit Product Attribute Map';
        $page_detail = 'Edit a Product Attribute Map of your vendor';
        // only allow auth user and admin
        if($productAttributeMap->vendor_id == auth()->user()->vendor_id)
        {
             $productattributes = ProductAttribute::where('vendor_id', auth()->user()->vendor_id)->get();
            return view('product_attribute_maps.edit', compact('productAttributeMap','productattributes','title', 'page_detail'));
        }else{
            return redirect()->back()->with('warning', 'Wrong url');
        }
    }

    public function update(ProductAttributeMapUpdate $request, ProductAttributeMap $productAttributeMap)
    {
        if($request->value) {
            $attribute_value = ProductAttributeMap::where([
                'vendor_id' => Auth::user()->vendor_id,
                'product_attribute_id' => $request->product_attribute_id,
                'value' => $request->value
            ])->where('id', '<>' , $productAttributeMap->id)
                ->value('value');
            if ($attribute_value){
                return redirect()->back()->with('error', 'Duplicate attribute value found please insert another value!');
            }
        }
        // check this attr map is already create for this vendor or not
        $checkAttr = ProductAttributeMap::where(['vendor_id' => auth()->user()->vendor_id, 'value' => strtolower($request->value), 'product_attribute_id' => $request->product_attribute_id])->first();
        if ($checkAttr)
        {
            return redirect()->back()->with('warning', ''.$request->value.' Attribute Map Already Exists !');
        }

        $productAttributeMap->update([
            'product_attribute_id' => $request->product_attribute_id,
            'value' =>$request->value,
            'updated_by' =>Auth::id(),
        ]);
        return redirect()->action('ProductAttributeMapController@index')->with('success', 'ProductAttributeMap Updated Success !');
    }


    public function destroy(ProductAttributeMap $productAttributeMap)
    {
        // only allow auth user and admin
        if($productAttributeMap->vendor_id == auth()->user()->vendor_id)
        {

            if ($productAttributeMap->productAttributeMapPurchasesDetails->count() > 0  )
            {
                return redirect()->back()->with('warning', $productAttributeMap->name. ' not allow to delete');
            }

            $productAttributeMap->delete();
            return redirect()->back()->with('success', 'ProductAttributeMap Deleted Success !');
        }else{
            return redirect()->back()->with('warning', 'Wrong url');
        }

    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $productAttributeMap = ProductAttributeMap::where('id', $request->id)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->first();
        if ($productAttributeMap)
        {
            $productAttributeMap->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }

    }


    public function getProductAttribute(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $attribute = ProductAttribute::where('vendor_id', $request->vendor_id)->get();
        if ($attribute)
        {
            $output = "<option value=''>Please Select</option>";
            foreach ($attribute as $attr)
            {
                $output .= "<option value='$attr->id'>$attr->name</option>";
            }
            return response()->json($output);
        }else{
            return response()->json('not found');
        }
    }

}
