<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Banner;
use Illuminate\Http\Request;
use App\Http\Requests\Banner\BannerStore;
use App\Http\Requests\Banner\BannerUpdate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use DataTables;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Banner Details';
        $page_detail = 'List of Banner Details';
        return view('banner.index',compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $banners = Banner::latest()->get();


        return DataTables::of($banners)
            ->addIndexColumn()

            ->editColumn('status', function ($banner) {
                if ($banner->status == 1) return '<span href="#0" id="ActiveUnactive" statusCode="'.$banner->status.'" data_id="'.$banner->id.'"   class="badge cursor-pointer badge-success">Active</span>';
                return '<span href="#0" id="ActiveUnactive" statusCode="'.$banner->status.'" data_id="'.$banner->id.'"   class="badge cursor-pointer badge-danger">Deactive</span>';
            })
            ->editColumn('image', function ($banner) {
                return '
                        <div class="pop_img" data-img="'.asset($banner->image).'">
                            <img width="50"
                                 src="'.asset($banner->image).'"
                                 alt="image">
                        </div>
                ';
            })
            ->addColumn('action', function ($banner) {
                return '<div class="btn-group">
                        <a href="/superadmin/banner/' . $banner->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteBanner('.$banner->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteBannerForm-'.$banner->id.'" action="/superadmin/banner/'. $banner->id .'">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['status','image','action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Banner';
        $page_detail = 'Create a Banner';
        return view('banner.create',compact('title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BannerStore $request)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {


        if($request->name) {
            $banner_name = Banner::where(['name' => $request->name])->value('name');
            if ($banner_name){
                return redirect()->back()->with('error', 'Duplicate banner name found please insert another name!');
            }
        }
        $request['status'] = $request->status ?? 0;
        $request['created_by'] =  auth()->user()->id;
        $request['updated_by'] =  auth()->user()->id;

        if ($request->type == 'banner'){
        $order = Banner::where('type', 'banner')->max('order');
        }elseif ($request->type == 'pop_up'){
            $order = Banner::where('type', 'pop_up')->max('order');
        }
        if ($order == null || $order == '')
            $order = 0;

        $order++;
        $slug = Str::slug($request->name, '-');


           $banner =  Banner::create([
                'url' => $request->url,
                'slug' => $slug,
                'meta_info' => $request->meta_info,
                'alt_info' => $request->alt_info,
                'type' => $request->type,
                'name' => $request->name,
                'order' => $order,
                'status' => $request->status,
                'created_by' => $request->created_by,
                'updated_by' => $request->updated_by,
            ]);

            if(!empty($request->image)) {
                // Start of Single Images Part
                $path_info = getPathInfo(['banner',$banner->id]);
                makeDirectory($path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $banner->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                $original_imageLocation = 'backend/uploads/banner/'.$banner->id.'/'.$setName;
                Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;

                $banner->image = $original_imageLocation;
                $banner->image_url = $image_url;
                $banner->save();
            }


            // Commit Transaction
            DB::commit();

            return redirect()->action('SuperAdmin\BannerController@index')->with('success', 'Banner Create Success !');

        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Banner $banner)
    {
        $title = 'Edit Banner';
        $page_detail = 'Edit a Banner';
        return view('banner.edit', compact('banner','title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BannerUpdate $request, Banner $banner)
    {

        // Begin Transaction
        DB::beginTransaction();
        try
        {

        if($request->name) {
            $banner_name = Banner::where([
//                'vendor_id' => Auth::user()->vendor_id,
                'name' => $request->name
            ])->where('id', '<>' , $banner->id)
                ->value('name');
            if ($banner_name){
                return redirect()->back()->with('error', 'Duplicate banner name found please insert another name!');
            }
        }
        $request['updated_by'] =  auth()->user()->id;
        $request['status'] = $request->status ?? 0;

            $banner->update([
                'url' => $request->url,
                'meta_info' => $request->meta_info,
                'alt_info' => $request->alt_info,
                'type' => $request->type,
                'name' => $request->name,
                'status' => $request->status,
                'updated_by' => $request->updated_by,
            ]);

            $banner_ids = Banner::select('id')->where([
                'type' => $request->type
            ])->get();
            $sort = 1;
            foreach ($banner_ids as $id) {
                Banner::where('id', $id->id)->update(['order' => $sort]);
                $sort++;
            }

            if(!empty($request->image)) {
                // Start of Single Images Part
                if ($banner->image)
                {
                    if (file_exists($banner->image)){
                        unlink($banner->image);
                    }
                }
                // End of delete product images
                $path_info = getPathInfo(['banner',$banner->id]);
                makeDirectory($path_info,0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];
                $setName = $banner->name . '-' . rand() . '-' . uniqid() .  '.' .$extension;

                $original_imageLocation = 'backend/uploads/banner/'.$banner->id.'/'.$setName;
                Image::make($img['data'])->save($path_info.'/'.$setName, 100);
                $image_url = URL::to('/'). '/' . $original_imageLocation;

                $banner->image = $original_imageLocation;
                $banner->image_url = $image_url;
                $banner->save();
            }

            // Commit Transaction
            DB::commit();

            return redirect()->action('SuperAdmin\BannerController@index')->with('success', 'Banner Updated Success !');



        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Banner $banner)
    {

        $type = $banner->type;

        if ($banner->image)
        {
            if (file_exists($banner->image)){
                unlink($banner->image);
            }
        }
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $banner->delete();

            $banner_ids = Banner::select('id')->where([
                'type' => $type
            ])->get();
            $sort = 1;
            foreach ($banner_ids as $id) {
                Banner::where('id', $id->id)->update(['order' => $sort]);
                $sort++;
            }

            // Commit Transaction
            DB::commit();

            return redirect()->back()->with('success', 'Banner Deleted Success !');



        } catch (Exception $exc) {

                // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function activeUnactive(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $banner = Banner::where('id', $request->id)->first();
        if ($banner)
        {
            $banner->update(['status' => $request->setStatus]);
            return response()->json('true');
        }else{
            return response()->json('false');
        }
    }
}
