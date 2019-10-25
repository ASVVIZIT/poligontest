<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Foundation\Auth\User;
use App\Laravue\Models\Order;

$factory->define(App\Laravue\Models\Report::class, function (Faker $faker) {
    //Start point of our date range.
    $start = strtotime("10 09 1971 00:00:00");
    //End point of our date range.
    $end = strtotime("22 06 1990 00:00:00");
    $timestamp = mt_rand($start, $end);
    return [
        'user_id' => User::all()->random()->id,
        'order_id' => Order::all()->random()->id,
        'status' => $faker->randomNumber(0, 2),
        'orders' => $faker->randomNumber(1, 10),
        'created_at' =>$faker->date('Y-m-d h:m:s', $timestamp),
    ];
});
