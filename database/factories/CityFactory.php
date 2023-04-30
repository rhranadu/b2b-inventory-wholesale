<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\City;
use App\State;
use App\User;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {
    return [
        'name' => $faker->state,
        'state_id' => function(){
            return   State::inRandomOrder()->first()->id;
        },
        'created_by' => 1,
        'updated_by' => 1,
    ];
});
