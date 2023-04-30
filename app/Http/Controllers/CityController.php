<?php

namespace App\Http\Controllers;

use App\City;
use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use App\Http\Requests\City\CityStore;
use App\Http\Requests\City\CityUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use DataTables;
class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        //$this->middleware('admin');
    }


    public function index()
    {
        Session::put('template', 'notika');
        $title = 'Cities';
        $page_detail = 'List of Cities';
        return view('single_vendor.cities.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $cities = City::with('state','createdBy','updatedBy')->latest()->get();
        return DataTables::of($cities)
            ->addIndexColumn()
            ->addColumn('action', function ($city) {
                return '<div class="btn-group">
                        <a href="/admin/city/' . $city->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteCity('.$city->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteCityForm-'.$city->id.'" action="/admin/city/' . $city->id . '">
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
        $states = State::all();
        $title = 'Create City';
        $page_detail = 'Create a City';
        return view('single_vendor.cities.create', compact('states','title', 'page_detail'));
    }


    public function store(CityStore $request)
    {

        City::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'created_by' => auth()->user()->id,
            'updated_by' =>  auth()->user()->id,
        ]);
        return redirect()->action('CityController@index')->with('success', 'City Created success!');
    }


    public function show(City $city)
    {
        return view('single_vendor.cities.view', compact('city'));
    }


    public function edit(City $city)
    {
        $states = State::all();
        $title = 'Edit City';
        $page_detail = 'Edit a City';
        return view('single_vendor.cities.edit', compact('city', 'states','title', 'page_detail'));
    }


    public function update(CityUpdate $request, City $city)
    {

        $city->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
            'updated_by' =>  auth()->user()->id,
        ]);
        return redirect()->action('CityController@index')->with('success', 'City Updated success!');
    }


    public function destroy(City $city)
    {
        $city->delete();
        return redirect()->back()->with('success', 'City deleted success!');
    }
}
