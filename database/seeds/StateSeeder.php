<?php

use App\Country;
use App\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{


    static $states = ['dhaka', 'islamabad', 'delhi'];

    public function run()
    {
        foreach (self::$states as $state)
        {
            State::create([
                'name' => $state,
                'country_id' => random_int(1,2),
                'created_by' => '1',
                'updated_by' => '1',
            ]);
        }
    }
}
