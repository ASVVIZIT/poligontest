<?php

use App\Laravue\Acl;
use Faker\Generator as Faker;
use App\Laravue\Models\Role;

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
    $start = strtotime("1961-09-10 12:10:10");
    //End point of our date range.
    $end = strtotime("1990-06-22 24:00:00");
    $gender = $faker->randomElement($array = array('male','female'));

    for($i = 0; $i < 10; $i++) {

        if ($gender == 'male') {
            $avatar_default = 'default_male.png';
        } else {
            $avatar_default = 'default_female.png';
        }

        $timestamp = mt_rand($start, $end);
        $arrayRundomTime = [null, date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp), date('Y-m-d h:m:s', $timestamp)];
        return [
            'name' => $faker->name,
            'firstname' => $faker->firstName,
            'surname' => $faker->name,
            'patronymic' =>$faker->lastName,
            'gender' => $gender,
            'avatar' => $avatar_default,
            'birthday' => $arrayRundomTime[rand(1, 4)],
            'email' => $faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'phone1' => $faker->phoneNumber,
            'phone2' => $faker->phoneNumber,
            'phone3' => $faker->phoneNumber,
            'phone4' => $faker->phoneNumber,
            'skype' => $faker->word,
            'address' => $faker->address,
            'password' => \Illuminate\Support\Facades\Hash::make('secretsecret'), // secretsecret
            'remember_token' => str_random(20),
        ];
    }
});
