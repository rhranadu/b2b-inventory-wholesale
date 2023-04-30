<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Country;
use App\User;
use Faker\Generator as Faker;

$factory->define(Country::class, function (Faker $faker) {
    return [
        'name' => $faker->country,
        'created_by' => 1,
        'updated_by' => 1,
    ];
});
