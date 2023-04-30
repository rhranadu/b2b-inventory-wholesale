<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Country;
use App\Vendor;
use App\City;
use App\UserType;
use App\State;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => bcrypt('12345678'), // password
        'mobile' => $faker->unique()->e164PhoneNumber,
        'country_id' =>  function(){
            return   Country::inRandomOrder()->first()->id;
        },
        'vendor_id' =>  function(){
            return   Vendor::inRandomOrder()->first()->id;
        },
        'user_type_id' =>  random_int(1,2),

        'state_id' =>  function(){
            return   State::inRandomOrder()->first()->id;
        },
        'city_id' =>  function(){
            return   City::inRandomOrder()->first()->id;
        },
        'post_code' => random_int(10,20),
        'gender' => 'male',
        'date_of_birth' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'image' => $faker->imageUrl(),
        'status' => random_int(0,1),
        'remember_token' => Str::random(10),
    ];
});
