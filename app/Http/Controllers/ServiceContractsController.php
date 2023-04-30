<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\ServiceContracts;
use Illuminate\Http\Request;

class ServiceContractsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service_contracts = ServiceContracts::
//            where([
//            'super_user_id' => auth()->id(),
//            ])->
        paginate(10);
        $title = 'Service Contracts';
        $page_detail = 'List of Service Contracts';
        return view('single_vendor.service_contracts.index',compact('service_contracts','title', 'page_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Service Contracts';
        $page_detail = 'Create a Service Contract';
        return view('single_vendor.service_contracts.create',compact('title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->title) {

            $name =  ServiceContracts::where([
                'title' => $request->title,
//                'super_user_id' => auth()->id(),
            ])->value('title');
            if ($name){
                return redirect()->back()->withInput()->with('error', 'Duplicate title found please insert another title!');
            }
        }
        if($request->position) {
            $position = ServiceContracts::where([
                'position' => $request->position,
//                'super_user_id' => auth()->id(),
            ])->value('position');
            if ($position){
                return redirect()->back()->withInput()->with('error', 'Duplicate position found please insert another position!');
            }
        }else{
            return redirect()->back()->withInput()->with('error', 'Required A position. please insert position!');
        }
        if(!$request->title){
            return redirect()->back()->withInput()->with('error', 'Please insert title!');
        }
        $request['super_user_id'] = auth()->id();
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();
        $serviceContract = ServiceContracts::create($request->except('_token'));

        return redirect()->action('ServiceContractsController@index')->with('success', 'Service Contract successfully add!');

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
        $serviceContracts =  ServiceContracts::where([
            'id' => $id,
//            'super_user_id' => auth()->id(),
        ])->first();
        $title = 'Edit Service Contracts';
        $page_detail = 'Edit a Service Contract';
        return view('single_vendor.service_contracts.edit', compact('serviceContracts','title', 'page_detail'));
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
        $serviceContracts =  ServiceContracts::where([
            'id' => $id,
//            'super_user_id' => auth()->id(),
        ])->first();
        if($request->title) {
            $name = ServiceContracts::where([
                'title' => $request->title,
//                'super_user_id' => auth()->id()
            ])->where('id', '<>' , $id)
                ->value('title');
            if ($name){
                return redirect()->back()->withInput()->with('error', 'Duplicate title found please insert another title!');
            }
        }
        if($request->position) {
            $position = ServiceContracts::where([
                'position' => $request->position,
//                'super_user_id' => auth()->id()
            ])->where('id', '<>' , $id)
                ->value('position');
            if ($position){
                return redirect()->back()->withInput()->with('error', 'Duplicate  position found please insert another position!');
            }
        }else{
            return redirect()->back()->withInput()->with('error', 'Required A position. please insert position!');
        }
        if(!$request->title){
            return redirect()->back()->withInput()->with('error', 'Please insert title!');
        }
        $request['super_user_id'] = auth()->user()->id;
        $request['vendor_id'] = auth()->user()->id;
        $request['updated_by'] = auth()->user()->id;
        $serviceContracts->update($request->except('_token'));
        return redirect()->action('ServiceContractsController@index')->with('success', 'Service Contract successfully update!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serviceContracts =  ServiceContracts::where([
            'id' => $id,
//            'super_user_id' => auth()->id(),
        ])->first();
        $serviceContracts->delete();
        return redirect()->back()->with('success', 'Service Contract deleted success !');
    }
}
