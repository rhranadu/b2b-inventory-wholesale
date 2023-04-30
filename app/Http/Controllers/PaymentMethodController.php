<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\PaymentMethod;
use App\Vendor;
use App\Http\Requests\PaymentMethod\PaymentMethodStore;
use App\Http\Requests\PaymentMethod\PaymentMethodUpdate;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment_methods = PaymentMethod::latest()->paginate(10);
        $title = 'Payment Method Details';
        $page_detail = 'List of Payment Method Details';
        return view('single_vendor.payment_methods.index',compact('payment_methods','title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $payment_methods = PaymentMethod::latest()->get();
        return DataTables::of($payment_methods)
            ->addIndexColumn()
            ->editColumn('logo', function ($payment_method) {
                if ($payment_method->logo)  return '
                        <div class="pop_img" data-img="'.asset($payment_method->logo).'">
                            <img width="50"
                                 src="'.asset($payment_method->logo).'"
                                 alt="logo">
                        </div>
                ';
                return '
                        <div>
                            No Logo
                        </div>
                ';
            })
            ->editColumn('status', function ($payment_method) {
                if ($payment_method->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$payment_method->status.'" data_id="'.$payment_method->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$payment_method->status.'" data_id="'.$payment_method->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->addColumn('action', function ($payment_method) {
                return '<div class="btn-group">
                        <a href="/admin/payment_methods/' . $payment_method->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteShippingMethod('.$payment_method->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteShippingMethodForm-'.$payment_method->id.'" action="/admin/payment_methods/' . $payment_method->id . '">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status','logo','action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Payment Method';
        $page_detail = 'Create a Payment Method';
        return view('single_vendor.payment_methods.create',compact('title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentMethodStore $request)
    {
        if($request->name) {
            $banner_name = PaymentMethod::where(['name' => $request->name])->value('name');
            if ($banner_name){
                return redirect()->back()->with('error', 'Duplicate Payment Method type found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? '0';
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();

        $paymentMethod = PaymentMethod::create($request->except('_token'));

        if(!empty($request->image)) {
            // Start of Single Images Part
            $path_info = getPathInfo(['payment_methods', $paymentMethod->id]);
            makeDirectory($path_info, 0755);

            $img = $request->image;
            $img = json_decode($img, true);
            $img_type = explode('/', $img['type']);
            $extension = $img_type[1];
            $setName = $paymentMethod->name . '-' . rand() . '-' . uniqid() . '.' . $extension;

            $original_imageLocation = 'backend/uploads/payment_methods/' . $paymentMethod->id . '/' . $setName;
            Image::make($img['data'])->save($path_info . '/' . $setName, 100);
            $image_url = URL::to('/') . '/' . $original_imageLocation;

            $paymentMethod->logo = $original_imageLocation;
            $paymentMethod->logo_url = $image_url;
            $paymentMethod->save();
        }


        return redirect()->action('PaymentMethodController@index')->with('success', 'Payment Method Created Success!');

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
    public function edit(PaymentMethod $paymentMethod)
    {
        $title = 'Edit Payment Method';
        $page_detail = 'Edit a Payment Method';
        return view('single_vendor.payment_methods.edit', compact('paymentMethod', 'title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentMethodUpdate $request, PaymentMethod $paymentMethod)
    {
        if($request->name) {
            $banner_name = PaymentMethod::where([
                'name' => $request->name
            ])->where('id', '<>' , $paymentMethod->id)
                ->value('name');
            if ($banner_name){
                return redirect()->back()->with('error', 'Duplicate Payment Method Type found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? '0';
        $request['updated_by'] = auth()->id();
        $paymentMethod->update($request->except('_token'));

        if(!empty($request->image)) {
            // Start of Single Images Part
            if ($paymentMethod->logo)
            {
                if (file_exists($paymentMethod->logo)){
                    unlink($paymentMethod->logo);
                }
            }
            $path_info = getPathInfo(['payment_methods', $paymentMethod->id]);
            makeDirectory($path_info, 0755);

            $img = $request->image;
            $img = json_decode($img, true);
            $img_type = explode('/', $img['type']);
            $extension = $img_type[1];
            $setName = $paymentMethod->name . '-' . rand() . '-' . uniqid() . '.' . $extension;

            $original_imageLocation = 'backend/uploads/payment_methods/' . $paymentMethod->id . '/' . $setName;
            Image::make($img['data'])->save($path_info . '/' . $setName, 100);
            $image_url = URL::to('/') . '/' . $original_imageLocation;

            $paymentMethod->logo = $original_imageLocation;
            $paymentMethod->logo_url = $image_url;
            $paymentMethod->save();
        }
        return redirect()->action('PaymentMethodController@index')->with('success', 'Paymnet Method Updated success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->back()->with('success', 'Payment Method deleted success!');
    }

    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $banner = PaymentMethod::where('id', $request->id)->first();
        if ($banner)
        {
            $banner->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }
}
