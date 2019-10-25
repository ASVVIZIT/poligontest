<?php

use Faker\Generator as Faker;

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

$factory->define(App\User::class, function (Faker $faker) {

    //Start point of our date range.
    $start = strtotime("10 09 1971");
    //End point of our date range.
    $end = strtotime("22 06 1990");
    $timestamp = mt_rand($start, $end);


    return [
        'name' => $faker->name,
        'firstname' => $faker->firstName,
        'surname' => $faker->name,
        'patronymic' =>$faker->lastName,
        'birthday' => $faker->date('Y-m-d', $timestamp),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secretsecret
        'remember_token' => str_random(10),
    ];
});
