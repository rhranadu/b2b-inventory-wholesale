<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_configs')->insert([
            [
                'name' => 'allowed_vendor_count',
                'value' => 1,
                'vendor_id' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
            [
                'name' => 'global_sale_commission_percentage',
                'value' => 0,
                'vendor_id' => null,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'deleted_at' => null
            ],
        ]);
    }
}
