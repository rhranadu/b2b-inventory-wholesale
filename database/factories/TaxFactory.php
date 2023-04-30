<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Tax;
use App\User;
use Faker\Generator as Faker;

$factory->define(Tax::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'percentage' => $faker->randomDigit(),
        'status' => random_int(0,1),
        'created_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
        'updated_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
    ];
});
