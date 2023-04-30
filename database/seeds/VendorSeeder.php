<?php

use App\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vendors = [
            [
                'name' => 'superadmin industries',
                'email' => 'superadminindustries@gmail.com',
                'phone' => '01521414629',
                'address' => 'pangsha rajbari',
                'website' => 'www.plabbd.com',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
            ],

            [
                'name' => 'admin industries',
                'email' => 'adminindustries@gmail.com',
                'phone' => '01720240475',
                'address' => 'pangsha babupara',
                'website' => 'www.plabbd.com',
                'status' => '1',
                'created_by' => '1',
                'updated_by' => '1',
            ],
        ];

        foreach ($vendors as $vendor)
            Vendor::create($vendor);
    }
}
