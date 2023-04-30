<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Http\Requests\Warehouse\WarehouseStore;
use App\Http\Requests\Warehouse\WarehouseUpdate;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use DataTables;
class WarehouseController extends Controller
{

    public function index()
    {
        $title = 'Warehouse';
        $page_detail = 'List of Warehouse';
        return view('warehouses.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $warehouses = Warehouse::where('vendor_id', auth()->user()->vendor_id)->latest()->get();

        return Datatables::of($warehouses)
            ->addIndexColumn()

            ->editColumn('status', function ($warehouse) {
                if ($warehouse->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$warehouse->status.'" data_id="'.$warehouse->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$warehouse->status.'" data_id="'.$warehouse->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($warehouse) {
                return '<div class="btn-group">
                        <a href="/admin/warehouse/' . $warehouse->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteWarehouse('.$warehouse->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteWarehouseForm-'.$warehouse->id.'" action="/admin/warehouse/'. $warehouse->id .'">
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
        $title = 'Create Warehouse';
        $page_detail = 'Create a Warehouse for your vendor';
        return view('warehouses.create',compact('title', 'page_detail'));
    }

    public function store(WarehouseStore $request)
    {
        if($request->name) {
            $warehouse_name = Warehouse::where(['vendor_id' => Auth::user()->vendor_id, 'name' => $request->name])->value('name');
            if ($warehouse_name){
                return redirect()->back()->with('error', 'Duplicate warehouse name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? 0;
        Warehouse::create([
            'vendor_id' => Auth::user()->vendor_id,
            'name' => $request->name,
            'address' => $request->address,
            'type' => $request->type,
            'status' => $request->status,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()->action('WarehouseController@index')->with('success', 'Warehouse Create Success !');
    }


    public function show (Warehouse $warehouse)
    {

    }

    public function edit(Warehouse $warehouse)
    {
        $title = 'Edit Warehouse';
        $page_detail = 'Edit a Warehouse for your vendor';
        // only allow auth user and admin
        if($warehouse->vendor_id == auth()->user()->vendor_id)
        {
            $vendors = Vendor::all();
            return view('warehouses.edit', compact('warehouse', 'vendors','title', 'page_detail'));
        }else{
           abort(404);
        }

    }

    public function update(WarehouseUpdate $request, Warehouse $warehouse)
    {

        if($request->name) {
            $warehouse_name = Warehouse::where([
                'vendor_id' => Auth::user()->vendor_id,
                'name' => $request->name
            ])->where('id', '<>' , $warehouse->id)
                ->value('name');
            if ($warehouse_name){
                return redirect()->back()->with('error', 'Duplicate warehouse name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? 0;
        $warehouse->update([
            'vendor_id' => Auth::user()->vendor_id,
            'name' => $request->name,
            'address' => $request->address,
            'status' => $request->status,
            'type' => $request->type,
            'updated_by' => Auth::id(),
        ]);
        return redirect()->action('WarehouseController@index')->with('success', 'Warehouse Updated Success !');
    }


    public function destroy(Warehouse $warehouse)
    {
        // only allow auth user and admin
        if($warehouse->vendor_id == auth()->user()->vendor_id)
        {

            if ($warehouse->warehouseUsers->count() > 0 || $warehouse->products->count() > 0 || $warehouse->warehouseSales->count() > 0 || $warehouse->returnProducts->count() > 0)
            {
                return redirect()->back()->with('warning', $warehouse->name. ' not allow to delete');
            }

            $warehouse->delete();
            return redirect()->back()->with('success', 'Warehouse deleted success');
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

        $warehouse =  Warehouse::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
        if ($warehouse)
       {
           $warehouse->update(['status' => $request->setStatus]);
           return response()->json('true');
       }else{
           return response()->json('false');
       }

    }

    public function warehouseList()
    {
        if (request()->ajax()) {
            $query = Warehouse::query();
            if (!empty(auth()->user()->warehouse_id)) {
                $query->where('id', auth()->user()->warehouse_id);
            } else {
                if (!empty(trim(request()->search))) {
                    $query->where('name', 'like', '%' . trim(request()->search) . '%');
                }
                if (!empty(auth()->user()->vendor_id)) {
                    $query->where('vendor_id', auth()->user()->vendor_id);
                }
            }
            $data = $query->pluck('name','id')->toArray();
            return response()->json($data, Response::HTTP_OK);
        }
    }


}
