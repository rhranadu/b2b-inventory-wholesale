<?php

namespace App\Http\Controllers\SuperAdmin;
use App\AdminConfig;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoice_settings = AdminConfig::where('name','invoice_prefix')->first();
        $title = 'Invoice Settings';
        $page_detail = 'List of Invoice Settings';
        return view('super_admin.admin_config.invoice_settings',compact('invoice_settings','title', 'page_detail'));
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
        $chk_invoice = AdminConfig::where('name','invoice_prefix')->first();
        if ($chk_invoice){
            return redirect()->back()->with('error', 'Already inserted one Invoice prefix!');
        }else{
            AdminConfig::create([
                'name' => $request->name,
                'value' => $request->value,
                'created_by' => auth()->id(),
                'updated_by' =>  auth()->id(),
            ]);
        }
        return redirect()->action('SuperAdmin\AdminConfigController@index')->with('success', 'Invoice Prefix Created success');
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
        $invoice_settings =  AdminConfig::where([
            'id' => $id,
        ])->first();
        $title = 'Edit Invoice Settings';
        $page_detail = 'Edit a Invoice Settings';
        return view('super_admin.admin_config.invoice_settings_edit', compact('invoice_settings','title', 'page_detail'));

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
        $invoice_settings =  AdminConfig::where([
            'id' => $id,
        ])->first();
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();
        $invoice_settings->update($request->except('_token'));
        return redirect()->action('SuperAdmin\AdminConfigController@index')->with('success', 'Invoice Prefix successfully update!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $check_invoice =  AdminConfig::where([
            'id' => $id,
        ])->first();
        $check_invoice->delete();
        return redirect()->back()->with('success', 'Invoice Prefix deleted success !');
    }

}
