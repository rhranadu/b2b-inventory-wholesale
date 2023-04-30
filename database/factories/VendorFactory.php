<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vendor;
use App\User;
use Faker\Generator as Faker;

$factory->define(Vendor::class, function (Faker $faker) {
    return [
        'name' => $faker->vendor,
        'address' => $faker->address,
        'email' => $faker->unique()->safeEmail,
        'logo' => $faker->imageUrl(),
        'favicon' => '',
        'website' => '',
        'status' => random_int(0,1),
        'created_by' => 1,
        'updated_by' => 1,
    ];
});
