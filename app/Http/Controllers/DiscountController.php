<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\Discount\DiscountStore;
use App\Http\Requests\Discount\DiscountUpdate;
use Illuminate\Http\Response;
use App\ProductBrand;
use App\Discount;
use App\Vendor;
use App\Product;
use App\ProductCategory;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Discounts';
        $page_detail = 'Create a Discount for your Vendor';
        return view('discounts.index',compact('title', 'page_detail'));
    }

    public function brandDiscount(Request $request)
    {
        $brands = ProductBrand::where('status',1)->where('vendor_id', auth()->user()->vendor_id)->get();
        $brandIds = [];
        foreach($brands as $brand){
            $brandIds[$brand->id] = $brand->id;
        }
        $discounts = Discount::where('discountable_type','product_brands')->whereIn('discountable_id',$brandIds)->paginate(10);
        $singleDiscount = [];
        if($request->has('id')){
            $singleDiscount = Discount::find($request->id);
        }
        return view('discounts.brand_discount', compact('discounts','brands','singleDiscount'));
    }

    public function vendorDiscount(Request $request)
    {
        if(getActiveGuard() == 'vendor'){
            $vendors = Vendor::where('status',1)->where('id', auth()->user()->vendor_id)->get();
            $vendorIds = [];
            foreach($vendors as $vendor){
                $vendorIds[$vendor->id] = $vendor->id;
            }
            $discounts = Discount::where('discountable_type','vendors')->whereIn('discountable_id',$vendorIds)->paginate(10);
        }elseif(getActiveGuard() == 'superadmin'){
            $vendors = Vendor::where('status',1)->get();
            $vendorIds = [];
            foreach($vendors as $vendor){
                $vendorIds[$vendor->id] = $vendor->id;
            }
            $discounts = Discount::where('discountable_type','vendors')->whereIn('discountable_id',$vendorIds)->paginate(10);
        }
        $singleDiscount = [];
        if($request->has('id')){
            $singleDiscount = Discount::find($request->id);
        }
        return view('discounts.vendor_discount', compact('discounts','vendors','singleDiscount'));
    }
    public function productCategoryDiscount(Request $request)
    {
        $categories = ProductCategory::where('status',1)/*->where('vendor_id', auth()->user()->vendor_id)*/->get();
        $categoryIds = [];
        foreach($categories as $category){
            $categoryIds[$category->id] = $category->id;
        }
        $parentCategories = ProductCategory::where('parent_category_id', null)->orWhere('parent_category_id', 0)->active()->get();
        $discounts = Discount::where('discountable_type','product_categories')->whereIn('discountable_id',$categoryIds)->paginate(10);
        $singleDiscount = [];
        if($request->has('id')){
            $singleDiscount = Discount::find($request->id);
        }
        return view('discounts.category_discount', compact('discounts','parentCategories','singleDiscount'));
    }
    public function productDiscount(Request $request)
    {
        $productIds =  Product::where('vendor_id', auth()->user()->vendor_id)->pluck('id')->toArray();

        $discounts = Discount::where('discountable_type','products')->whereIn('discountable_id',$productIds)->paginate(10);
        $singleDiscount = [];
        $product = [];
        if($request->has('id')){
            $singleDiscount = Discount::find($request->id);
            $product =  Product::with('productBrand','productCategory', 'product_images', 'productVendor', 'parentProduct')->where('id', $singleDiscount->discountable_id)->get()[0];
        }
        return view('discounts.product_discount', compact('discounts','singleDiscount','product'))->render();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!empty($request->selected_product_arr[0])) {
            $selected_product_arr = explode(',', $request->selected_product_arr[0]);
        }
        if($request->has('id')){
            $discount = Discount::find($request->id);
        } else {
            $discount = new Discount();
            $discount->created_by = auth()->id();
        }
        if ($request->type_tab == 'product' && !$request->discountable_id){
            if(!empty($selected_product_arr)) {
                foreach ($selected_product_arr as $product_single_id){
                    Discount::create([
                        'title' => $request->title,
                        'discount_amount' => $request->discount_amount,
                        'start_at' => $request->start_at,
                        'end_at' => $request->end_at,
                        'is_ongoing' => $request->is_ongoing ?? '0',
                        'status' => $request->status ?? '0',
                        'discount_percentage' => $request->discount_percentage,
                        'discount_for' => $request->discount_for,
                        'is_approved' => 1,
                        'discountable_id' => $product_single_id,
                        'discountable_type' => $request->discountable_type,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }
            }
        }else{
            !isset($request->discountable_type) ?: $discount->discountable_type = $request->discountable_type;
            !isset($request->discountable_id) ?: $discount->discountable_id = $request->discountable_id;
            !isset($request->title) ?: $discount->title = $request->title;
            !isset($request->discount_amount) ?: $discount->discount_amount = $request->discount_amount;
            !isset($request->discount_percentage) ?: $discount->discount_percentage = $request->discount_percentage;
            !isset($request->discount_for) ?: $discount->discount_for = $request->discount_for;
            !isset($request->start_at) ?: $discount->start_at = $request->start_at;
            !isset($request->end_at) ?: $discount->end_at = $request->end_at;
            !isset($request->is_ongoing) ?: $discount->is_ongoing  = $request->is_ongoing ;
            $discount->updated_by = auth()->id();
            $discount->status = $request->status ?? 0;
            $discount->is_ongoing = $request->is_ongoing ?? 0;
            if($request->is_ongoing == 1){
                $discount->end_at = null;
            }elseif ($request->end_at){
                $discount->is_ongoing = 0;
            }
            $discount->is_approved = 1; // need to implement superadmin vefication later
            $discount->save();
        }
        return redirect()->action(
            [DiscountController::class, 'index'], ['type_tab' => $request->type_tab]
        )->with('success', 'Discount Created success!');

//        return redirect()->action('DiscountController@index')->with('success', 'Discount Created success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discount $discount)
    {
        // need to be implement some logic. i.e: Delete forbidden if there is any discounted active entity
        $discount->delete();
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
    public function productSelection()
    {
        $vendors = Vendor::active()->get();
        $products = Product::get();
        $parentCategories = ProductCategory::where('parent_category_id', null)->orWhere('parent_category_id', 0)->active()->get();
        return view('discounts.product_selection', compact('parentCategories','products','vendors'));
    }

    public function getProductListForSelection(Request $request)
    {
        $category = 0;
        foreach($request->category as $v){
            if(!empty($v)){
                $category = $v;
            }
        }
        $searchString = $request->searchString;
        $vendor = $request->vendor;
        $products = Product::with('productBrand','productCategory', 'product_images', 'productVendor', 'parentProduct')
            ->where([
                ['status', 1],
                ['vendor_id', auth()->user()->vendor_id],
                ['is_approved', 1],
                ['name' , 'like', '%'.$searchString.'%']
                ]);
        if(!empty($category)){
            $products->where('product_category_id', $category);
        }
        if(!empty($vendor)){
            $products->where('vendor_id', $vendor);
        }
        $products = $products->paginate(10);
        return view('discounts.product_list_for_selection', compact('products'));
    }

    public function activeUnactive(Request $request)
    {

        if (!$request->ajax()) {
            abort(404);
        }
        $discountDetailActive = Discount::where('id', $request->id)->first();

        if ($request->setStatus == 1){
            $discountDetailInactives = Discount::where([
                    ['discountable_id', $discountDetailActive->discountable_id],
                    ['discountable_type', $discountDetailActive->discountable_type],
                    ['discount_for', $discountDetailActive->discount_for],
                    ])
                ->where('id', '<>' , $request->id)
                ->get();
            if($discountDetailInactives){
                foreach ($discountDetailInactives as $discountDetailInactive){
                    $discountDetailInactive->update(['status' => 0]);
                }
            }
        }
        if ($discountDetailActive) {
            $discountDetailActive->update(['status' => $request->setStatus]);
            return response()->json('true');
        } else {
            return response()->json('false');
        }

    }
}
