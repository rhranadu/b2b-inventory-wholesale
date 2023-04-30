<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;


use App\PromotionalAdvertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class PromotionalAdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $promotional_ads = PromotionalAdvertisement::
//                where([
//                    'super_user_id' => auth()->id(),
//                ])->
                paginate(10);
        $title = 'Promotional Advertisement';
        $page_detail = 'List of Promotional Ads';
        return view('single_vendor.promotional_advertisements.index',compact('promotional_ads','title', 'page_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Promotional Advertisement';
        $page_detail = 'Create a Promotional Advertisement';
        return view('single_vendor.promotional_advertisements.create',compact('title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $total_check = PromotionalAdvertisement::
            where([
            //'super_user_id' => auth()->id(),
            'status' => 1,
            ])->
            get();
            if (count($total_check) > 5) {
                return redirect()->action('PromotionalAdvertisementController@create')->withInput()->with('error', 'Can not set more than 5 Images for promotional advertisement!');
            } else {
                if (!$request->name) {
                    return redirect()->back()->withInput()->with('error', 'Please insert name!');
                }
                if ($request->position == '0'){
                    return redirect()->back()->withInput()->with('error', 'Promotional Ad position 0 not valid, please insert another position!');
                }
                if ($request->name) {
                    $ads_name = PromotionalAdvertisement::where([
                        'name' => $request->name,
                        //'super_user_id' => auth()->id(),
                    ])->value('name');
                    if ($ads_name) {
                        return redirect()->back()->withInput()->with('error', 'Duplicate Promotional Ad name found please insert another name!');
                    }
                }
                if ($request->position) {
                    $ads_position = PromotionalAdvertisement::where([
                        'position' => $request->position,
                        //'super_user_id' => auth()->id(),
                    ])->value('position');
                    if ($ads_position) {
                        return redirect()->back()->withInput()->with('error', 'Duplicate Promotional Ad position found please insert another position!');
                    }
                }else{
                    return redirect()->back()->withInput()->with('error', 'Required Promotional Ad position. please insert position!');
                }

                $request['status'] = $request->status ?? '0';
                $request['super_user_id'] = auth()->id();
                $request['created_by'] = auth()->id();
                $request['updated_by'] = auth()->id();
                $allowedfileExtension = ['jpeg', 'gif', 'svg', 'jpg', 'png'];

                $promotionalAd = PromotionalAdvertisement::create($request->except('_token', 'img'));
                if (!empty($request->image)) {

                    $path_info =
                        getPathInfo(['promotionalAds', 'images', 'original', auth()->id(), $promotionalAd->id]);
                    $thumbnail_path_info =
                        getPathInfo(['promotionalAds', 'images', 'thumbnail', auth()->id(), $promotionalAd->id]);
                    makeDirectory($path_info, 0755);
                    makeDirectory($thumbnail_path_info, 0755);

                    $img = $request->image;
                    $img = json_decode($img, true);
                    $img_type = explode('/', $img['type']);
                    $extension = $img_type[1];
                    $setName = $promotionalAd->name . '-' . rand() . '-' . uniqid() . '.' . $extension;

                    $check = in_array($extension, $allowedfileExtension);
                    if (!$check) {
                        return redirect()->action('PromotionalAdvertisementController@create')->with('error', 'Please insert these formats jpeg,png,jpg,gif,svg!');
                    }

                    $original_imageLocation = 'backend/uploads/payment_methods/' . $promotionalAd->id . '/' . $setName;
                    Image::make($img['data'])->save($path_info . '/' . $setName, 100);
                    $image_url = URL::to('/') . '/' . $original_imageLocation;

                    // for thumbnail image

                    $thumbnail_imageLocation =
                        'backend/uploads/promotionalAds/images/thumbnail/' . auth()->id() . '/' . $promotionalAd->id . '/' . $setName;
                    Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info . '/' . $setName, 100);
                    $thumbnail_image_url = URL::to('/') . '/' . $thumbnail_imageLocation;

                    $promotionalAd->image = $original_imageLocation;
                    $promotionalAd->image_url = $image_url;
                    $promotionalAd->thumbnail_image = $thumbnail_imageLocation;
                    $promotionalAd->thumbnail_image_url = $thumbnail_image_url;
                    $promotionalAd->save();
                } else {
                    return redirect()->action('PromotionalAdvertisementController@create')->withInput()->with('error', 'Please Upload Image!');
                }
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput()->with(['code' => 0, 'msg' => 'Failed to process', 'raw' => $exception->getMessage()]);
        }
        DB::commit();

        return redirect()->action('PromotionalAdvertisementController@index')->withInput()->with('success', 'Promotional Ad successfully add!');

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
    public function edit(PromotionalAdvertisement $promotionalAdvertisement)
    {
        $title = 'Edit Promotional Ads';
        $page_detail = 'Edit a Promotional Ads';
        return view('single_vendor.promotional_advertisements.edit', compact('promotionalAdvertisement','title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PromotionalAdvertisement $promotionalAdvertisement)
    {
        DB::beginTransaction();
        try {
            if (!$request->name) {
                return redirect()->back()->with('error', 'Please insert name!');
            }
            if ($request->position == '0'){
                return redirect()->back()->with('error', 'Promotional Ad position 0 not valid, please insert another position!');
            }
            if ($request->name) {
                $ads_name = PromotionalAdvertisement::where([
                    'name' => $request->name,
                    //'super_user_id' => auth()->id()
                ])->where('id', '<>', $promotionalAdvertisement->id)
                    ->value('name');
                if ($ads_name) {
                    return redirect()->back()->with('error', 'Duplicate Promotional Ad name found please insert another name!');
                }
            }
            if ($request->position) {
                $ads_position = PromotionalAdvertisement::where([
                    'position' => $request->position,
                    //'super_user_id' => auth()->id()
                ])->where('id', '<>', $promotionalAdvertisement->id)
                    ->value('position');
                if ($ads_position) {
                    return redirect()->back()->with('error', 'Duplicate Promotional Ad position found please insert another position!');
                }
            }else{
                return redirect()->back()->with('error', 'Required Promotional Ad position. please insert position!');
            }

            $request['super_user_id'] = auth()->id();
            $request['status'] = $request->status ?? 0;
            $request['vendor_id'] = auth()->user()->id;
            $request['updated_by'] = auth()->user()->id;
            $promotionalAdvertisement->update($request->except('img'));


            if (!empty($request->image)) {
                $allowedfileExtension = ['jpeg', 'gif', 'svg', 'jpg', 'png'];

                if ($promotionalAdvertisement->image) {
                    if (file_exists($promotionalAdvertisement->image)) {
                        unlink($promotionalAdvertisement->image);
                        unlink($promotionalAdvertisement->thumbnail_image);
                    }
                }
                $path_info =
                    getPathInfo(['promotionalAds', 'images', 'original', auth()->id(), $promotionalAdvertisement->id]);
                $thumbnail_path_info =
                    getPathInfo(['promotionalAds', 'images', 'thumbnail', auth()->id(), $promotionalAdvertisement->id]);
                makeDirectory($path_info, 0755);
                makeDirectory($thumbnail_path_info, 0755);

                $img = $request->image;
                $img = json_decode($img, true);
                $img_type = explode('/', $img['type']);
                $extension = $img_type[1];

                $check = in_array($extension, $allowedfileExtension);
                if (!$check) {
                    return redirect()->action('PromotionalAdvertisementController@edit')->with('error', 'Please insert these formats jpeg,png,jpg,gif,svg!');
                }

                $setName = $promotionalAdvertisement->name . '-' . rand() . '-' . uniqid() . '.' . $extension;
                $original_imageLocation =
                    'backend/uploads/promotionalAds/images/original/' . auth()->id() . '/' . $promotionalAdvertisement->id . '/' . $setName;
                Image::make($img['data'])->save($path_info . '/' . $setName, 100);
                $image_url = URL::to('/') . '/' . $original_imageLocation;


                // for thumbnail image

                $thumbnail_imageLocation =
                    'backend/uploads/promotionalAds/images/thumbnail/' . auth()->id() . '/' . $promotionalAdvertisement->id . '/' . $setName;
                Image::make($img['data'])->resize(100, 100)->save($thumbnail_path_info . '/' . $setName, 100);
                $thumbnail_image_url = URL::to('/') . '/' . $thumbnail_imageLocation;

                $promotionalAdvertisement->image = $original_imageLocation;
                $promotionalAdvertisement->image_url = $image_url;
                $promotionalAdvertisement->thumbnail_image = $thumbnail_imageLocation;
                $promotionalAdvertisement->thumbnail_image_url = $thumbnail_image_url;
                $promotionalAdvertisement->save();

            } else {
                return redirect()->action('PromotionalAdvertisementController@edit')->with('error', 'Please Upload Image!');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->with(['code' => 0, 'msg' => 'Failed to process', 'raw' => $exception->getMessage()]);
        }
        DB::commit();
        return redirect()->action('PromotionalAdvertisementController@index')->with('success', 'Promotional Ad successfully add!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PromotionalAdvertisement $promotionalAdvertisement)
    {
        if ($promotionalAdvertisement->image != null) {
            if (file_exists($promotionalAdvertisement->image)){
                unlink($promotionalAdvertisement->image);
                unlink($promotionalAdvertisement->thumbnail_image);
            }
        }
        $promotionalAdvertisement->delete();
        return redirect()->back()->with('success', 'Promotional Ads deleted success !');
    }
}
