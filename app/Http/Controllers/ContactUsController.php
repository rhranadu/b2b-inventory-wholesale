<?php

namespace App\Http\Controllers;
use App\ContactUs;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contact_us = ContactUs::
//        where([
//            'super_user_id' => auth()->id(),
//        ])->
        paginate(10);

        foreach ($contact_us as $contact){
            $contact->social_links = json_decode($contact->social_links,true);
        }
        $title = 'Contact Us';
        $page_detail = 'List of Contact Us';
        return view('single_vendor.contact_us.index',compact('contact_us','title', 'page_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Contact Us';
        $page_detail = 'Create a Contact Us';
        return view('single_vendor.contact_us.create',compact('title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->phone || !$request->email || !$request->address){
            return redirect()->back()->withInput()->with('error', 'Please insert phone, email and address!');
        }
        if($request->email) {

            $email =  ContactUs::where([
                'email' => $request->email,
//                'super_user_id' => auth()->id(),
            ])->value('email');
            if ($email){
                return redirect()->back()->withInput()->with('error', 'Duplicate Email found please insert another Email!');
            }
        }
        if(!empty($request['store_icon_name'])){
            $arr =[];
            foreach ($request['store_icon_name'] as $key => $value)
            {
                $data['icon'] = $request['store_icon_name'][$key];
                $data['link'] = $request['store_link'][$key];
                $data['position'] = $request['store_position'][$key];
                array_push($arr,$data);

            }
            $request['social_links'] = json_encode($arr);
        }else{
            $request['social_links'] = null;
        }
        $request['super_user_id'] = auth()->id();
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();
        $contactUs = ContactUs::create($request->except('_token'));
        return redirect()->action('ContactUsController@index')->with('success', 'Contact Us successfully add!');


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
        $contactUs =  ContactUs::where([
            'id' => $id,
//            'super_user_id' => auth()->id(),
        ])->first();
        $title = 'Edit Contact Us';
        $page_detail = 'Edit a Contact Us';
        return view('single_vendor.contact_us.edit', compact('contactUs','title', 'page_detail'));
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
        $contactUs =  ContactUs::where([
            'id' => $id,
//            'super_user_id' => auth()->id(),
        ])->first();

        if(!$request->phone || !$request->email || !$request->address){
            return redirect()->back()->withInput()->with('error', 'Please insert phone, email and address!');
        }
        if($request->email) {
            $email = ContactUs::where([
                'email' => $request->email,
//                'super_user_id' => auth()->id()
            ])->where('id', '<>' , $id)
                ->value('email');
            if ($email){
                return redirect()->back()->withInput()->with('error', 'Duplicate Email found please insert another Email!');
            }
        }

        if(!empty($request['store_icon_name'])){
            $arr =[];
            foreach ($request['store_icon_name'] as $key => $value)
            {
                $data['icon'] = $request['store_icon_name'][$key];
                $data['link'] = $request['store_link'][$key];
                $data['position'] = $request['store_position'][$key];
                array_push($arr,$data);

            }
            $request['social_links'] = json_encode($arr);
        }else{
            $request['social_links'] = null;
        }
        $request['super_user_id'] = auth()->id();
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();

        $contactUs->update($request->except('_token'));
        return redirect()->action('ContactUsController@index')->with('success', 'Contact Us successfully update!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact_us =  ContactUs::where([
            'id' => $id,
//            'super_user_id' => auth()->id(),
        ])->first();
        $contact_us->delete();
        return redirect()->back()->with('success', 'Contact Us deleted success !');
    }

    public function getSocialByAjax(Request $request){
        $socials =  ContactUs::where([
            'id' => $request->id,
//            'super_user_id' => auth()->id(),
        ])->select('social_links')->first();
        $socials = json_decode($socials->social_links,  true);
        return response()->json(['success'=>true,'socials'=>$socials]);
    }
}
