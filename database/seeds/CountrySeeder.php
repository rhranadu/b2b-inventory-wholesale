<?php

use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{


    static $countries = ['bangladesh', 'pakistan', 'india'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::$countries as $country) {
            \App\Country::create([
                'name' => $country,
                'created_by' => '1',
                'updated_by' => '1',
            ]);
        }
    }
}
