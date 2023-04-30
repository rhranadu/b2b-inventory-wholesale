<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    public function install() {
        try {
            DB::connection()->getPdo();

            $tables = DB::select('SHOW TABLES');
            $migration_files = glob(database_path() . '/migrations/*.php');
            if (count($tables) != count($migration_files)) {
                try {
                    Artisan::call('migrate');
                } catch (\Exception $exception) {
                    dd($exception);
                }
                return redirect('/');
            }
        } catch (\Exception $e) {
            dd("Could not connect to the database.  Please check your configuration. error:" . $e->getMessage() );
        }
    }
}
