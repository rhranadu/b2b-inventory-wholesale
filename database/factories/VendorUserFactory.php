<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vendor;
use App\User;
use App\Supplier;
use Faker\Generator as Faker;

$factory->define(Supplier::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'email' => $faker->email,
        'address' => $faker->address,
        'mobile' => '65465465',
        'details' => $faker->paragraph,
        'type' => 'customer',
        'image' => $faker->imageUrl(),
        'website' => '',
        'vendor_id' => function(){
            return   Vendor::inRandomOrder()->first()->id;
        },
        'status' => random_int(0,1),
        'created_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
        'updated_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
    ];
});
