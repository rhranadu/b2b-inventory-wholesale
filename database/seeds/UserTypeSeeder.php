<?php

use App\UserType;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    static $types = ['super_users', 'vendor_users','marketplace_users'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$types as $type)
        {
            UserType::create([
                'table_name' => $type,
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

    }
}
