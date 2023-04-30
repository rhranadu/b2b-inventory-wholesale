<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vendor;
use App\ProductCategory;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProductCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->word(),
        'image' => $faker->imageUrl(),
        'parent_category_id' => random_int(1,10),
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
