<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\GSM::class, function (Faker $faker) {
    return [
        'day_id' => $faker->date('Y/m/d'),
        'hour_id' => $faker->date('H'),
        'location' => $faker->city,
        'access' => number_format($faker->randomFloat(2, 98, 100), 2),
        'lost' => number_format($faker->randomFloat(2, 0, 0.3), 2),
        'handover' => number_format($faker->randomFloat(2, 98, 100), 2),
    ];
});
