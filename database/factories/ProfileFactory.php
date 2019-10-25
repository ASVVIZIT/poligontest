<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'firstname' => $faker->firstName,
        'surname' => $faker->name,
        'patronymic' =>$faker->lastName,
        'birthday' => $faker->date('Y-m-d', $timestamp),
        'email' => $faker->unique()->safeEmail,
        'location' => $faker->unique()->address,
    ];
});
