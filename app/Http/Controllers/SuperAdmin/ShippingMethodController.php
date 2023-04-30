<?php
namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingMethod\ShippingMethodStore;
use App\Http\Requests\ShippingMethod\ShippingMethodUpdate;
use App\ShippingMethod;
use App\Vendor;
use Illuminate\Http\Request;
use DataTables;

class ShippingMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shipping_methods = ShippingMethod::with('vendor')->latest()->paginate(10);
        $title = 'Shipping Method Details';
        $page_detail = 'List of Shipping Method Details';
        return view('super_admin.shipping_methods.index',compact('shipping_methods','title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $shipping_methods = ShippingMethod::with('vendor')->latest()->get();
        return DataTables::of($shipping_methods)
            ->addIndexColumn()
            ->editColumn('status', function ($shipping_method) {
                if ($shipping_method->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$shipping_method->status.'" data_id="'.$shipping_method->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$shipping_method->status.'" data_id="'.$shipping_method->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($shipping_method) {
                return '<div class="btn-group">
                        <a href="/superadmin/shipping_methods/' . $shipping_method->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteShippingMethod('.$shipping_method->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteShippingMethodForm-'.$shipping_method->id.'" action="/superadmin/shipping_methods/' . $shipping_method->id . '">
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
        $vendors = Vendor::where('status',1)->get();
        $title = 'Create Shipping Method';
        $page_detail = 'Create a Shipping Method';
        return view('super_admin.shipping_methods.create',compact('vendors','title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShippingMethodStore $request)
    {
        if($request->name) {
            $banner_name = ShippingMethod::where(['name' => $request->name,'vendor_id'=> $request->vendor_id])->value('name');
            if ($banner_name){
                return redirect()->back()->with('error', 'Duplicate Shipping Method name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? '0';
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();
        $shippingMethod = ShippingMethod::create($request->except('_token'));
        return redirect()->action('SuperAdmin\ShippingMethodController@index')->with('success', 'Shipping Method Created Success!');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ShippingMethod $shippingMethod)
    {
        $vendors = Vendor::where('status',1)->get();
        $title = 'Edit Shipping Method';
        $page_detail = 'Edit a Shipping Method';
        return view('super_admin.shipping_methods.edit', compact('shippingMethod', 'vendors','title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShippingMethodUpdate $request, ShippingMethod $shippingMethod)
    {
        if($request->name) {
            $banner_name = ShippingMethod::where([
                'vendor_id' => $request->vendor_id,
                'name' => $request->name
            ])->where('id', '<>' , $shippingMethod->id)
                ->value('name');
            if ($banner_name){
                return redirect()->back()->with('error', 'Duplicate Shipping Method name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? '0';
        $request['updated_by'] = auth()->id();
        $shippingMethod->update($request->except('_token'));
        return redirect()->action('SuperAdmin\ShippingMethodController@index')->with('success', 'Shipping Method Updated success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingMethod $shippingMethod)
    {
        $shippingMethod->delete();
        return redirect()->back()->with('success', 'Shipping Method deleted success!');
    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $banner = ShippingMethod::where('id', $request->id)->first();
        if ($banner)
        {
            $banner->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }
}
