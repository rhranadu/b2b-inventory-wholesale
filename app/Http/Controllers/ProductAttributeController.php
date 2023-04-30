<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Http\Requests\ProductAttribute\ProductAttributeStore;
use App\Http\Requests\ProductAttribute\ProductAttributeUpdate;
use App\ProductAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class ProductAttributeController extends Controller
{
    public function index()
    {
        $title = 'Product Attributes';
        $page_detail = 'List of product attributes';
        return view('product_attributes.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $product_attributes = ProductAttribute::with('updatedBy', 'attributeMaps')
            ->where('vendor_id', auth()->user()->vendor_id)
            ->latest()
            ->get();

        return Datatables::of($product_attributes)
            ->addIndexColumn()

            ->editColumn('status', function ($product_attribute) {
                if ($product_attribute->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_attribute->status.'" data_id="'.$product_attribute->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$product_attribute->status.'" data_id="'.$product_attribute->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($product_attribute) {
                return '<div class="btn-group">
                        <a href="/admin/product_attribute/' . $product_attribute->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteAttribute('.$product_attribute->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteAttributeForm-'.$product_attribute->id.'" action="/admin/product_attribute/'. $product_attribute->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    public function create()
    {
        $title = 'Create Product Attribute';
        $page_detail = 'Create a Product Attribute for your vendor';
        return view('product_attributes.create',compact('title', 'page_detail'));
    }

    public function store(ProductAttributeStore $request)
    {
        if($request->name) {
            $attribute_name = ProductAttribute::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
            if ($attribute_name){
                return redirect()->back()->with('error', 'Duplicate attribute name found please insert another name!');
            }
        }
        // check this attr is already create for this vendor or not
        $checkAttr = ProductAttribute::where(['vendor_id' => auth()->user()->vendor_id, 'name' => strtolower($request->name)])->first();
        if ($checkAttr)
        {
            return redirect()->back()->with('warning', ''.$request->name.' Attribute Already Exists !');
        }
        $request['status'] = $request->status ?? 0;
        ProductAttribute::create([
            'name' => $request->name,
            'vendor_id' => Auth::user()->vendor_id,
            'status' =>$request->status,
            'created_by' => Auth::id(),
            'updated_by' =>Auth::id(),
        ]);
        return redirect()->action('ProductAttributeController@index')->with('success', 'ProductAttribute Create Success !');
    }


    public function show (ProductAttribute $productAttribute)
    {

    }

    public function edit(ProductAttribute $productAttribute)
    {
        $title = 'Edit Product Attribute';
        $page_detail = 'Edit a Product Attribute of your vendor';
        // only allow auth user and admin
        if($productAttribute->vendor_id == auth()->user()->vendor_id)
        {
            return view('product_attributes.edit', compact('productAttribute','title', 'page_detail'));
        }else{
            abort(404);
        }

    }

    public function update(ProductAttributeUpdate $request, ProductAttribute $productAttribute)
    {

        if($request->name) {
            $attribute_name = ProductAttribute::where([
                'vendor_id' => Auth::user()->vendor_id,
                'name' => $request->name
            ])->where('id', '<>' , $productAttribute->id)
                ->value('name');
            if ($attribute_name){
                return redirect()->back()->with('error', 'Duplicate attribute name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? 0;
        $productAttribute->update([
            'name' => $request->name,
            'status' => $request->status,
            'updated_by' =>Auth::id(),
        ]);
        return redirect()->action('ProductAttributeController@index')->with('success', 'ProductAttribute Updated Success !');
    }


    public function destroy(ProductAttribute $productAttribute)
    {
        // only allow auth user and admin
        if($productAttribute->vendor_id == auth()->user()->vendor_id)
        {

            if ($productAttribute->attributeMaps->count() > 0 || $productAttribute->productAttributePurchasesDetails->count() > 0  )
            {
                return redirect()->back()->with('warning', $productAttribute->name. 'not allow to delete');
            }

            $productAttribute->delete();
            return redirect()->back()->with('success', 'ProductAttribute Deleted Success !');
        }else{
            abort(404);
        }

    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $productAttribute = ProductAttribute::where('id', $request->id)
            ->where('vendor_id', auth()->user()->vendor_id)
            ->first();
        if ($productAttribute)
        {
            $productAttribute->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }

    }

}
