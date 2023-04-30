<?php

use App\City;
use App\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    static $cities = ['Rajbary', 'Karachi', 'Burari'];

    public function run()
    {
        foreach (self::$cities as $city) {
            City::create([
                'name' => $city,
                'state_id' => random_int(1,2),
                'created_by' => '1',
                'updated_by' => '1',
            ]);
        }
    }
}
