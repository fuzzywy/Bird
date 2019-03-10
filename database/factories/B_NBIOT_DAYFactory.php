<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\NBIOT_DAY::class, function (Faker $faker) {
    return [
        'day_id' => $faker->date('Y/m/d'),
        'location' => $faker->city,
        'access' => number_format($faker->randomFloat(2, 98, 100), 2),
        'interfererate' => number_format($faker->randomFloat(2, 0, 45), 2),
    ];
});
