<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vendor;
use App\ProductBrand;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProductBrand::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
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
