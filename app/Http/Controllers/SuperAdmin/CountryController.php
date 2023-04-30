<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Country;
use App\Http\Controllers\Controller;
use App\Http\Requests\Country\CountryStore;
use App\Http\Requests\Country\CountryUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use DataTables;
class CountryController extends Controller
{

    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('superadmin');
    }


    public function index()
    {
        $title = 'Countries';
        $page_detail = 'List of countries';
        return view('super_admin.countries.index', compact('title', 'page_detail'));
    }


    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $countries = Country::with('createdBy','updatedBy')->latest()->get();
        return DataTables::of($countries)
            ->addIndexColumn()
            ->addColumn('action', function ($country) {
                return '<div class="btn-group">
                        <a href="/superadmin/country/' . $country->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteCountry('.$country->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteCountryForm-'.$country->id.'" action="/superadmin/country/' . $country->id . '">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['action'])
            ->make(true);

    }


    public function create()
    {
        $title = 'Create Country';
        $page_detail = 'Create a Country';
        return view('super_admin.countries.create', compact('title', 'page_detail'));
    }


    public function store(CountryStore $request)
    {

        Country::create([
            'name' => $request->name,
            'created_by' => auth()->user()->id,
            'updated_by' =>  auth()->user()->id,
        ]);
        return redirect()->action('SuperAdmin\CountryController@index')->with('success', 'Country Created success');
    }


    public function show(Country $country)
    {
        return view('super_admin.countries.view', compact('country'));
    }


    public function edit(Country $country)
    {
        $title = 'Edit Country';
        $page_detail = 'Edit a Country';
        return view('super_admin.countries.edit', compact('country','title', 'page_detail'));
    }


    public function update(CountryUpdate $request, Country $country)
    {

        $country->update([
            'name' => $request->name,
            'updated_by' =>  auth()->user()->id,
        ]);
        return redirect()->action('SuperAdmin\CountryController@index')->with('success', 'Country Updated success!');
    }


    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->back()->with('success', 'Country deleted success!');
    }
}
