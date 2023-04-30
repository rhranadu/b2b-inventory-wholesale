<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller {
    public function index(Request $request) {
        if (getActiveGuard() != 'superadmin') {
            return redirect('/')->with('error', 'You are in the wrong place!!');
        }
        if (!$request->ajax()) {
            return view('super_admin.artisan.index');   
        }
        try {
            Artisan::call(trim($request->command));
        } catch (\Exception $exc) {
            return response()->json(['error' => true,'data' => false ,'msg' => $exc->getMessage()], Response::HTTP_OK);
        }
        return response()->json(['error' => false, 'data' => "<pre>" . Artisan::output() ,'msg' => 'Success'], Response::HTTP_OK);
    }
}
