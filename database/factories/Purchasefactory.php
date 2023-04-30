<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vendor;
use App\Product;
use App\ProductAttribute;
use App\Purchase;
use App\User;
use App\Supplier;
use App\Warehouse;
use Faker\Generator as Faker;

$factory->define(Purchase::class, function (Faker $faker) {
    return [
        'invoice_no' => $faker->randomNumber(),
        'status' => random_int(0,1),
        'product_id' => function(){
            return   Product::inRandomOrder()->first()->id;
        },
        'attribute_id' => function(){
            return   ProductAttribute::inRandomOrder()->first()->id;
        },
        'warehouse_id' => function(){
            return   Warehouse::inRandomOrder()->first()->id;
        },
        'supplier_id' => function(){
            return  Supplier::inRandomOrder()->first()->id;
        },
        'created_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
        'updated_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
    ];
});
