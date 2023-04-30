<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\State;
use App\Country;
use App\User;
use Faker\Generator as Faker;

$factory->define(State::class, function (Faker $faker) {
    return [
        'name' => $faker->state,
        'country_id' => function(){
            return   Country::inRandomOrder()->first()->id;
        },
        'created_by' => 1,
        'updated_by' => 1,
    ];
});
