<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImage\ProductImageStore;
use App\Http\Requests\ProductImage\ProductImageUpdate;
use App\Product;
use App\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\URL;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('product_images')
            ->where([
                ['vendor_id', '=', auth()->user()->vendor_id],
                ['status', '=', 1],
            ])
//            ->where('vendor_id', auth()->user()->vendor_id)
            ->get();
        return view('product_image.index',compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $request['created_by'] =  auth()->user()->id;
        $request['updated_by'] =  auth()->user()->id;
        $allowedfileExtension=['jpeg','gif','svg','jpg','png'];
        $images = $request->file('img');

        // Begin Transaction
        DB::beginTransaction();
        try
        {

            makeDirectory(getPathInfo(['products','images','original',$request->product_id]),0755);
            makeDirectory(getPathInfo(['products','images','x_600',$request->product_id]),0755);
            makeDirectory(getPathInfo(['products','images','x_300',$request->product_id]),0755);
            makeDirectory(getPathInfo(['products','images','x_100',$request->product_id]),0755);
            makeDirectory(getPathInfo(['products','images','x_50',$request->product_id]),0755);

            if(!$request->product_id) {
                return redirect()->action('ProductImageController@index')->with('error', 'Please Select Product Name!');
            }
            if(!$images) {
                return redirect()->action('ProductImageController@index')->with('error', 'Please Upload Image!');
            }
            if ($images)
            {

                if(count($images) > 5) {
                    return redirect()->action('ProductImageController@index')->with('error', 'Can not upload more than 5 Images at a time!');
                }

                foreach($images as $image){
//                    dd($image);
                    $real_name = $image->getClientOriginalName();
                    $extension = $image->getClientOriginalExtension();
                    $check=in_array($extension,$allowedfileExtension);
                    $filesize = Image::make($image)->filesize();
                    $height = Image::make($image)->height();
                    $width = Image::make($image)->width();

                    if($filesize > 2048000){
                        return redirect()->action('ProductImageController@index')->with('error', 'Maximum file size to upload is 2MB (2048 KB)!');
                    }
                    if(!$check){
                        return redirect()->action('ProductImageController@index')->with('error', 'Please insert these formats jpeg,png,jpg,gif,svg!');
                    }
                    if($height < 1200 && $width < 1200){
                        return redirect()->action('ProductImageController@index')->with('error', 'The Image has invalid image dimensions correct dimension is (1200*1200)!');
                    }

                    $setName = rand() . '-' . uniqid() . '.' .$image->getClientOriginalExtension();

                    if ($height == 1200 && $width == 1200) {
                        $imageLocation = 'backend/uploads/products/images/original/'.$request->product_id.'/'.$setName;
                        Image::make($image)->save(public_path('backend/uploads/products/images/original/'.$request->product_id.'/'.$setName), 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                    }else {
                        $imageLocation = 'backend/uploads/products/images/original/'.$request->product_id.'/'.$setName;
                        Image::make($image)->resize(1200, 1200)->save(public_path('backend/uploads/products/images/original/'.$request->product_id.'/'.$setName), 100);
                        $request['original_path'] = $imageLocation;
                        $request['original_path_url'] = URL::to('/'). '/' . $imageLocation;
                    }

                    $imageLocation = 'backend/uploads/products/images/x_600/'.$request->product_id.'/'.$setName;
                    Image::make($image)->resize(600, 600)->save(public_path('backend/uploads/products/images/x_600/'.$request->product_id.'/'.$setName), 100);
                    $request['x_600_path'] = $imageLocation;
                    $request['x_600_url'] = URL::to('/'). '/'  . $imageLocation;


                    $imageLocation = 'backend/uploads/products/images/x_300/'.$request->product_id.'/'.$setName;
                    Image::make($image)->resize(300, 300)->save(public_path('backend/uploads/products/images/x_300/'.$request->product_id.'/'.$setName), 100);
                    $request['x_300_path'] = $imageLocation;
                    $request['x_300_url'] = URL::to('/'). '/' . $imageLocation;


                    $imageLocation = 'backend/uploads/products/images/x_100/'.$request->product_id.'/'.$setName;
                    Image::make($image)->resize(100, 100)->save(public_path('backend/uploads/products/images/x_100/'.$request->product_id.'/'.$setName), 100);
                    $request['x_100_path'] = $imageLocation;
                    $request['x_100_url'] = URL::to('/'). '/' . $imageLocation;


                    $imageLocation = 'backend/uploads/products/images/x_50/'.$request->product_id.'/'.$setName;
                    Image::make($image)->resize(50, 50)->save(public_path('backend/uploads/products/images/x_50/'.$request->product_id.'/'.$setName), 100);
                    $request['x_50_path'] = $imageLocation;
                    $request['x_50_url'] = URL::to('/') . '/' . $imageLocation;


                    $request['type'] = $image->getClientOriginalExtension();


                    $order = ProductImage::where('product_id', $request->product_id)->max('order');

                    if ($order == null || $order == '')
                        $order = 0;

                    $order++;

                    ProductImage::create([
                        'type' => $request->type,
                        'product_id' => $request->product_id,
                        'order' => $order,
                        'original_path' => $request->original_path,
                        'original_path_url' => $request->original_path_url,
                        'x_600_path' => $request->x_600_path,
                        'x_600_url' => $request->x_600_url,
                        'x_300_path' => $request->x_300_path,
                        'x_300_url' => $request->x_300_url,
                        'x_100_path' => $request->x_100_path,
                        'x_100_url' => $request->x_100_url,
                        'x_50_path' => $request->x_50_path,
                        'x_50_url' => $request->x_50_url,
                        'created_by' => $request->created_by,
                        'updated_by' => $request->updated_by,
                    ]);


                }

            }

            // Commit Transaction
            DB::commit();

            return redirect()->action(
                [ProductImageController::class, 'index'], ['product_id' => $request->product_id]
            )->with('success', 'Product Image Create Success !');

        }
        catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }

//        return redirect()->action('ProductImageController@index')->with('success', 'Product Image Create Success !');


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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {


        $image_id = $request->val_image_id;
        $productImage = ProductImage::where('id', $image_id)->first();
        // Begin Transaction
        DB::beginTransaction();
        try
        {
            if ($productImage->original_path)
            {
                if (file_exists($productImage->original_path)){
                    unlink($productImage->original_path);
                    unlink($productImage->x_600_path);
                    unlink($productImage->x_300_path);
                    unlink($productImage->x_100_path);
                    unlink($productImage->x_50_path);
                }

            }

            $productImage->delete();

            // Commit Transaction
            DB::commit();

            return response()->json(['success'=> true]);



        } catch (Exception $exc) {

        // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }


    public function getProductImages($id){
        $product_images = ProductImage::select('id','x_100_path')->where('product_id', $id)->orderBy('order','ASC')->get();
        return response()->json(['product_images'=>$product_images]);
    }

    public function itemsSort(Request $request) {

        // Begin Transaction
        DB::beginTransaction();
        try
        {
            $sort = 1;
            foreach ($request->ids as $id) {
                ProductImage::where('id', $id)->update(['order' => $sort]);
                $sort++;
            }

            // Commit Transaction
            DB::commit();

            return response()->json(['success'=> true]);


        } catch (Exception $exc) {

            // Rollback Transaction
            DB::rollback();

            return redirect()->back()->with('error', $exc->getMessage());
        }
    }

}
