<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ProductAttribute;
use App\ProductAttributeMap;
use App\Vendor;
use App\User;
use Faker\Generator as Faker;

$factory->define(ProductAttributeMap::class, function (Faker $faker) {
    return [
        'product_attribute_id' => function(){
            return   ProductAttribute::inRandomOrder()->first()->id;
        },
        'vendor_id' => function(){
            return   Vendor::inRandomOrder()->first()->id;
        },
        'value' => $faker->hexColor,
        'created_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
        'updated_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
    ];
});
