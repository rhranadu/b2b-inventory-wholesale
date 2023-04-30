<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\Http\Requests\WarehouseDetail\WarehouseDetailStore;
use App\Http\Requests\WarehouseDetail\WarehouseDetailUpdate;
use App\Warehouse;
use App\WarehouseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use DataTables;
class WarehouseDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Warehouse Details';
        $page_detail = 'List of warehouse details';
        return view('warehouse_detail.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $warehouse_details = WarehouseDetail::with('warehouse')->where('vendor_id', auth()->user()->vendor_id)->orderBy('id', 'asc')->get();

        foreach ($warehouse_details as $warehouse_detail) {
            if($warehouse_detail->parent_section_id) {
                $warehouse_detail['parent_section_name'] = WarehouseDetail::where('id', $warehouse_detail->parent_section_id)->value('section_name');
            }
        }

        return DataTables::of($warehouse_details)
            ->addIndexColumn()

            ->editColumn('status', function ($warehouse_detail) {
                if ($warehouse_detail->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$warehouse_detail->status.'" data_id="'.$warehouse_detail->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$warehouse_detail->status.'" data_id="'.$warehouse_detail->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($warehouse_detail) {
                return '<div class="btn-group">
                        <a href="/admin/warehouse_detail/' . $warehouse_detail->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteWarehouse('.$warehouse_detail->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteWarehouseForm-'.$warehouse_detail->id.'" action="/admin/warehouse_detail/'. $warehouse_detail->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status','action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Warehouse Deatil';
        $page_detail = 'Create a Warehouse Deatil for your Vendor';
        $warehouses = Warehouse::where('vendor_id', auth()->user()->vendor_id)->get();
        return view('warehouse_detail.create', compact('warehouses','title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WarehouseDetailStore $request)
    {

        if($request->section_name) {
            $section_name = WarehouseDetail::where(['vendor_id' => Auth::user()->vendor_id,'warehouse_id'=> $request->warehouse_id, 'section_name' => $request->section_name])->value('section_name');
            if ($section_name){
                return redirect()->back()->with('error', 'Duplicate Section Name found please insert another one!');
            }
            $section_code = WarehouseDetail::where(['vendor_id' => Auth::user()->vendor_id,'warehouse_id'=> $request->warehouse_id, 'section_code' => $request->section_code])->value('section_code');
            if ($section_code){
                return redirect()->back()->with('error', 'Duplicate Section Code found please insert another one!');
            }
        }
        DB::transaction(function () use ($request) {
            $request['status'] = $request->status ?? 0;
            WarehouseDetail::create([
                'warehouse_id' => $request->warehouse_id,
                'vendor_id' => auth()->user()->vendor_id,
                'section_code' => $request->section_code,
                'section_name' => $request->section_name,
                'parent_section_id' => $request->parent_section_id,
                'status' => $request->status,
                'created_by' => auth()->user()->id,
                'updated_by' => Auth::id(),
            ]);
        });
        return redirect()->action('WarehouseDetailController@index')->with('success', 'Warehouse Details Created Successfully !');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(WarehouseDetail $warehouse_details)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(WarehouseDetail $warehouseDetail)
    {
        $title = 'Edit Warehouse Deatil';
        $page_detail = 'Edit a Warehouse Deatil for your Vendor';
        // only allow auth user and admin
        if($warehouseDetail->vendor_id == auth()->user()->vendor_id)
        {
            $warehouseDetail['warehouse_name'] = Warehouse::where('id', $warehouseDetail->warehouse_id)->value('name');
            if($warehouseDetail->parent_section_id){
                $parentSections = WarehouseDetail::select('id','parent_section_id','section_name')->where('warehouse_id', $warehouseDetail->warehouse_id)->get();
            }
            else{
                $parentSections = WarehouseDetail::where('id', '<>',$warehouseDetail->id)->where('warehouse_id', $warehouseDetail->warehouse_id)->select('id','parent_section_id','section_name')->get();
            }
            foreach ($parentSections as &$parent_section){
                if ($parent_section->parent){
                    $parent_section->section_name =  $parent_section->parent->section_name . ' >> ' . $parent_section->section_name;
                }
            }
            $vendors = Vendor::all();
            return view('warehouse_detail.edit', compact('warehouseDetail', 'vendors','parentSections','title', 'page_detail'));
        }else{
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(WarehouseDetailUpdate $request, WarehouseDetail $warehouseDetail)
    {

        try {
            $request['status'] = $request->status ?? 0;
            $warehouseDetail->update([
                'vendor_id' => Auth::user()->vendor_id,
                'section_code' => $request->section_code,
                'section_name' => $request->section_name,
                'parent_section_id' => $request->parent_section_id,
                'status' => $request->status,
                'updated_by' => Auth::id(),
            ]);
            return redirect()->action('WarehouseDetailController@index')->with('success', 'Warehouse Details Updated Successfully !');
        } catch (Exception $exc) {
            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarehouseDetail $warehouseDetail)
    {
        // only allow auth user and admin
        if ($warehouseDetail->vendor_id == auth()->user()->vendor_id) {

            $child_sections = WarehouseDetail::where('parent_section_id',$warehouseDetail->id)->get();
//            foreach ($child_sections as $child_section) {
//                $child_section->parent_section_id == NULL;
//                $child_section->save();
//            }

            if ($child_sections->count() > 0 || $warehouseDetail->products->count() > 0) {
                return redirect()->back()->with('warning', $warehouseDetail->section_name . ' not allow to delete because of dependency');
            }
//            if ($warehouseDetail->products->count() > 0) {
//                return redirect()->back()->with('warning', $warehouseDetail->section_name . ' not allow to delete');
//            }

                $warehouseDetail->delete();
                return redirect()->back()->with('success', 'Warehouse Detail deleted success !');
        } else {
            abort(404);
        }
    }


    public function getWarehouseType($id){
        $warehouse = Warehouse::where('id', $id)->value('type');
        $parent_sections = WarehouseDetail::select('id','parent_section_id','section_name')->where('warehouse_id', $id)->get();
        $ps = [];
        foreach ($parent_sections as $parent_section){
            if ($parent_section->parent){
                if(isset($ps[$parent_section->parent->id]) && !empty($ps[$parent_section->parent->id])){
                    $ps[$parent_section->id]['id'] = $parent_section->id;
                    $ps[$parent_section->id]['section_name'] = $ps[$parent_section->parent->id]['section_name'] . ' >> ' . $parent_section->section_name;
                }else{
                    $ps[$parent_section->id]['id'] = $parent_section->id;
                    $ps[$parent_section->id]['section_name'] = $parent_section->parent->section_name;
                }
            } else {
                $ps[$parent_section->id]['id'] = $parent_section->id;
                $ps[$parent_section->id]['section_name'] = $parent_section->section_name;
            }
        }
        return response()->json(['warehouse'=>$warehouse,'parent_sections'=>$ps]);
    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $warehouseDetail = WarehouseDetail::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();

        if ($warehouseDetail) {
            $warehouseDetail->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }

    public function WarehouseSectionTreeAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        if (!empty($request->id)) {
            $warehouseDetail =
                WarehouseDetail::with('warehouse')->where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
                if (!empty($warehouseDetail)) {
                    $tree = [
                        [
                            'id' => $warehouseDetail->warehouse->id,
                            'parent' => '#',
                            'text' => $warehouseDetail->warehouse->name,
                            'icon' => '//jstree.com/tree.png',
                            'state' => [
                                'opened' => true, // is the node open
                                'disabled' => false, // is the node disabled
                                'selected' => true, // is the node selected
                            ],
                        ],
                        [
                            'id' => $warehouseDetail->id . '',
                            'parent' => empty($warehouseDetail->parent_section_id) ? $warehouseDetail->warehouse->id : $warehouseDetail->parent_section_id,
                            'text' => $warehouseDetail->section_name,
                            'icon' => '',
                            "state" => array("opened" => empty($warehouseDetail->parent_section_id) ? true : false)
                        ],
                    ];

                    if (!empty($warehouseDetail->parent_section_id)) {
                        $wD = $warehouseDetail;

                        while (!empty($wD->parent_section_id)) {
                            $wD =
                                WarehouseDetail::where('id', $wD->parent_section_id)->where('vendor_id', auth()->user()->vendor_id)->first();

                            $a = array(
                                "id" => $wD->id,
                                "parent" => empty($wD->parent_section_id) ? $warehouseDetail->warehouse->id : $wD->parent_section_id,
                                "text" => $wD->section_name,
                                "state" => array("opened" => empty($wD->parent_section_id) ? true : false)
                            );
                            array_push($tree, $a);
                        }
                    }

                    echo json_encode($tree);
                    exit;
                }
        }
    }

    public function WarehouseTreeAjax(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        if (!empty($request->id)) {
            $warehouse =
                Warehouse::where('id', $request->id)->where('vendor_id', auth()->user()->vendor_id)->first();
                if (!empty($warehouse)) {
                    $tree = [
                            'id' => $warehouse->id,
                            'parent' => '#',
                            'text' => $warehouse->name,
                            'icon' => '//jstree.com/tree.png',
                            'state' => [
                                'opened' => true, // is the node open
                                'disabled' => false, // is the node disabled
                                'selected' => true, // is the node selected
                            ]
                        ];
                    echo json_encode($tree);
                    exit;
                }
        }
    }
}
