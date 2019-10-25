<?php

use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;

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

$factory->define(App\Laravue\Models\Order::class, function (Faker $faker) {
    //Start point of our date range.
    $start = strtotime("10 09 1971 00:00:00");
    //End point of our date range.
    $end = strtotime("22 06 1990 00:00:00");
    $timestamp = mt_rand($start, $end);
    return [
        'user_id' => User::all()->random()->id,
        'status' => $faker->randomNumber(0, 2),
        'orders' => $faker->randomNumber(1, 10),
        'created_at' =>$faker->date('Y-m-d h:m:s', $timestamp),
    ];
});
