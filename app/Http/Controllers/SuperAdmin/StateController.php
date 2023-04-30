<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Country;
use App\Http\Controllers\Controller;
use App\State;
use App\Http\Requests\State\StateStore;
use App\Http\Requests\State\StateUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use DataTables;
class StateController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('superadmin');
    }


    public function index()
    {
        $title = 'States';
        $page_detail = 'List of States';
        return view('super_admin.states.index', compact('title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $states = State::with('country','createdBy','updatedBy')->latest()->get();
        return DataTables::of($states)
            ->addIndexColumn()
            ->addColumn('action', function ($state) {
                return '<div class="btn-group">
                        <a href="/superadmin/state/' . $state->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteState('.$state->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteStateForm-'.$state->id.'" action="/superadmin/state/' . $state->id . '">
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
        $countries = Country::all();
        $title = 'Create State';
        $page_detail = 'Create a State';
        return view('super_admin.states.create', compact('countries','title', 'page_detail'));
    }


    public function store(StateStore $request)
    {

        State::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'created_by' => auth()->user()->id,
            'updated_by' =>  auth()->user()->id,
        ]);
        return redirect()->action('SuperAdmin\StateController@index')->with('success', 'State Created success!');
    }


    public function show(State $state)
    {

        return view('super_admin.states.view', compact('state'));
    }


    public function edit(State $state)
    {
        $countries = Country::all();
        $title = 'Edit State';
        $page_detail = 'Edit a State';
        return view('super_admin.states.edit', compact('state', 'countries','title', 'page_detail'));
    }


    public function update(StateUpdate $request, State $state)
    {

        $state->update([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'updated_by' =>  auth()->user()->id,
        ]);
        return redirect()->action('SuperAdmin\StateController@index')->with('success', 'State Updated success!');
    }


    public function destroy(State $state)
    {
        $state->delete();
        return redirect()->back()->with('success', 'State deleted success!');
    }
}
