<?php

namespace App\Http\Controllers;

use App\Tax;
use App\Vendor;
use App\Http\Requests\ProductTax\ProductTaxStore;
use App\Http\Requests\ProductTax\ProductTaxUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class TaxController extends Controller
{

    public function index()
    {
        $title = 'Product Tax';
        $page_detail = 'List of taxes';
        return view('taxes.index', compact('title', 'page_detail'));
    }
    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $taxes = Tax::where('vendor_id', auth()->user()->vendor_id)->latest()->get();


        return Datatables::of($taxes)
            ->addIndexColumn()

            ->editColumn('status', function ($tax) {
                if ($tax->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$tax->status.'" data_id="'.$tax->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$tax->status.'" data_id="'.$tax->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($tax) {
                return '<div class="btn-group">
                        <a href="/admin/tax/' . $tax->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteTax('.$tax->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteTaxForm-'.$tax->id.'" action="/admin/tax/'. $tax->id .'">
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
        $title = 'Create Tax';
        $page_detail = 'Create a tax amount for your vendor';
        return view('taxes.create',compact('title', 'page_detail'));
    }

    public function store(ProductTaxStore $request)
    {
        if($request->name) {
            $tax_name = Tax::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
            if ($tax_name){
                return redirect()->back()->with('error', 'Duplicate tax name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? 0;
        $request['vendor_id'] = $request->vendor_id ?? auth()->user()->vendor_id;
        $request['created_by'] =  auth()->user()->id;
        $request['updated_by'] =  auth()->user()->id;

        Tax::create($request->except('_token'));

        return redirect()->action('TaxController@index')->with('success', 'Tax Create Success !');
    }


    public function show (Tax $tax)
    {

    }

    public function edit(Tax $tax)
    {
        $title = 'Edit Tax';
        $page_detail = 'Edit a tax amount of your vendor';
        // only allow auth user and admin
        if($tax->vendor_id == auth()->user()->vendor_id)
        {
            return view('taxes.edit', compact('tax','title', 'page_detail'));
        }else{
            return redirect()->back()->with('warning', 'Wrong url');
        }

    }

    public function update(ProductTaxUpdate $request, Tax $tax)
    {

        if($request->name) {
            $tax_name = Tax::where([
                'vendor_id' => Auth::user()->vendor_id,
                'name' => $request->name
            ])->where('id', '<>' , $tax->id)
                ->value('name');
            if ($tax_name){
                return redirect()->back()->with('error', 'Duplicate tax name found please insert another name!');
            }
        }

        $request['status'] = $request->status ?? 0;
        $request['vendor_id'] = $request->vendor_id ?? auth()->user()->vendor_id;
        $request['updated_by'] =  auth()->user()->id;
        $tax->update($request->all());
        return redirect()->action('TaxController@index')->with('success', 'Tax Updated Success !');
    }


    public function destroy(Tax $tax)
    {
        // only allow auth user and admin
        if($tax->vendor_id == auth()->user()->vendor_id)
        {
            if ($tax->taxProducts->count() > 0)
            {
                return redirect()->back()->with('warning', $tax->name. ' not allow to delete');
            }
            $tax->delete();
            return redirect()->back()->with('success', 'Tax Deleted Success !');
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

        $tax =  Tax::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
        if ($tax)
        {
            $tax->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }



}
