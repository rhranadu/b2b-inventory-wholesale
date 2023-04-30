<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\MpExternalPage;
use Illuminate\Http\Request;

class ExternalPagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $external_pages = MpExternalPage::
        paginate(10);

        $title = 'External Pages';
        $page_detail = 'List of External Pages';
        return view('super_admin.external_pages.index', compact('external_pages', 'title', 'page_detail'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create External Pages';
        $page_detail = 'Create a External Pages';
        return view('super_admin.external_pages.create', compact('title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->title || !$request->slug || !$request->descriptions) {
            return redirect()->back()->with('error', 'Please insert Title, Slug and Description!');
        }
        if ($request->slug) {
            $slug = MpExternalPage::where([
                'slug' => $request->slug,
            ])->value('slug');
            if ($slug) {
                return redirect()->back()->with('error', 'Duplicate Slug found please insert another Slug!');
            }
        }

        $request['status'] = $request->status ? $request->status : 0;
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();
        $external_page = MpExternalPage::create($request->except('_token'));
        return redirect()->action('SuperAdmin\ExternalPagesController@index')->with('success', 'External Page successfully add!');


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $external_page = MpExternalPage::where([
            'id' => $id,
        ])->first();

        $title = 'Edit External Page';
        $page_detail = 'Edit a External Page';
        return view('super_admin.external_pages.edit', compact('external_page', 'title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $MpExternalPage = MpExternalPage::where([
            'id' => $id,
        ])->first();

        if (!$request->title || !$request->slug || !$request->descriptions) {
            return redirect()->back()->with('error', 'Please insert Title, Slug and Description!');
        }
        if ($request->slug) {
            $slug = MpExternalPage::where([
                'slug' => $request->slug,
            ])->where('id', '<>', $id)
                ->value('slug');
            if ($slug) {
                return redirect()->back()->with('error', 'Duplicate Slug found please insert another Email!');
            }
        }

        $request['status'] = $request->status ? $request->status : 0;
        $request['created_by'] = auth()->id();
        $request['updated_by'] = auth()->id();

        $MpExternalPage->update($request->except('_token'));
        return redirect()->action('SuperAdmin\ExternalPagesController@index')->with('success', 'Contact Us successfully update!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $MpExternalPage = MpExternalPage::where([
            'id' => $id,
        ])->first();

        $MpExternalPage->delete();
        return redirect()->back()->with('success', 'Contact Us deleted success !');
    }
}
