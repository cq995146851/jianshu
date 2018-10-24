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

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt(123456),
        'remember_token' => str_random(10),
        'created_at' => date("Y-m-d H:i:s", time()),
        'updated_at' => date("Y-m-d H:i:s", time()),
        'activation_token' => md5(uniqid()),
        'activated' => false
    ];
});
