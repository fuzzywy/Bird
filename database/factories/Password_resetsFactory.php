<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Password_resets::class, function (Faker $faker) {
    return [
		'email' => $faker->safeEmail,
		'token' => str_random(10),
        'created_at' => $faker->dateTimeThisYear
    ];
});
