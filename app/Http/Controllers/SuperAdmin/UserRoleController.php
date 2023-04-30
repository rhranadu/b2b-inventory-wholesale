<?php

namespace App\Http\Controllers\SuperAdmin;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRole\UserRoleStore;
use App\Http\Requests\UserRole\UserRoleUpdate;
use App\UserRole;
use App\UserType;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Str;

class UserRoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
        $this->middleware('superadmin');
    }


    public function index()
    {
        $user_roles = UserRole::with('user_type')->get();
        $title = 'Roles';
        $page_detail = 'List of User Roles';
        return view('super_admin.user_roles.index', compact('user_roles','title', 'page_detail'));
    }

    public function getListByAjax(Request $request)
    {
        if (!$request->ajax())
        {
            abort(404);
        }
        $user_roles = UserRole::with('user_type')->get();
        return DataTables::of($user_roles)
            ->addIndexColumn()
            ->addColumn('action', function ($user_role) {
                return '<div class="btn-group">
                        <a href="/superadmin/user_role/' . $user_role->id . '/edit" class="btn btn-sm btn-icon btn-warning" data-toggle="tooltip" data-placement="auto" title="EDIT" data-original-title="EDIT"><i class="fas fa-pencil-alt"></i></a>
                        <a href="#0" class="btn btn-sm btn-icon btn-danger" onclick="deleteUserRole('.$user_role->id.')" data-toggle="tooltip" data-placement="auto" title="DELETE" data-original-title="DELETE"><i class="fa fa-trash"></i></a>
                        </div>
                        <form style="display: none" method="POST" id="deleteUserRoleForm-'.$user_role->id.'" action="/superadmin/user_role/' . $user_role->id . '">
                        '.csrf_field().'
                        <input type="hidden" name="_method" value="DELETE">
                        </form>
                        ';
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_types = UserType::where('status',1)->get();
        $title = 'Create User Role';
        $page_detail = 'Create a User Role';
        return view('super_admin.user_roles.create',compact('user_types','title', 'page_detail'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRoleStore $request)
    {
        $userRole = UserRole::create($request->except('_token'));
        return redirect()->action('SuperAdmin\UserRoleController@index')->with('success', 'User Role Created Success!');

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
    public function edit(UserRole $userRole)
    {
        $user_types = UserType::where('status',1)->get();
        $title = 'Edit User Role';
        $page_detail = 'Edit a User Role';
        return view('super_admin.user_roles.edit', compact('userRole','user_types','title', 'page_detail'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRoleUpdate $request, UserRole $userRole)
    {
        if($request->name) {
            $userRole_name = UserRole::where([
                'name' => $request->name
            ])->where('id', '<>' , $userRole->id)
                ->value('name');
            if ($userRole_name){
                return redirect()->back()->with('error', 'Duplicate Role name found please insert another name!');
            }
        }
        $userRole->update($request->except('_token'));
        return redirect()->action('SuperAdmin\UserRoleController@index')->with('success', 'User Role Updated Success!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserRole $userRole)
    {
        $userRole->delete();
        return redirect()->back()->with('success', 'City deleted success!');
    }
}
