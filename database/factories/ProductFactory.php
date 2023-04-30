<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Vendor;
use App\Product;
use App\ProductAttribute;
use App\ProductBrand;
use App\ProductCategory;
use App\Manufacturer;
use App\Tax;
use App\User;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'image_path' => $faker->imageUrl(),
        'product_model' => $faker->century,
        'product_details' => $faker->paragraph,
//        'bar_code' => $faker->ean8,
        'qr_code' => $faker->isbn10,
        'model_no' => $faker->ean8,
        'product_specification' => $faker->paragraph,
        'status' => random_int(0,1),

        'vendor_id' => function(){
            return   Vendor::inRandomOrder()->first()->id;
        },
        'tax_id' => function(){
            return   Tax::inRandomOrder()->first()->id;
        },
        'product_category_id' => function(){
            return   ProductCategory::inRandomOrder()->first()->id;
        },
        'product_brand_id' => function(){
            return  ProductBrand::inRandomOrder()->first()->id;
        },
        'manufacturer_id' => function(){
            return   Manufacturer::inRandomOrder()->first()->id;
        },
        /*'product_attribute_id' => function(){
            return   ProductAttribute::inRandomOrder()->first()->id;
        },*/
        'created_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
        'updated_by' => function(){
            return   User::inRandomOrder()->first()->id;
        },
        'min_price' => random_int(50,1000),
        'max_price' => random_int(60,1100),
        'alert_quantity' => 5,
    ];
});
