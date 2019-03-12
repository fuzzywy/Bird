<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Business::class, function (Faker $faker) {
    return [
        'day_id' => $faker->date('Y-m-d'),
        'city' => $faker->randomElement(['无锡', '常州', '苏州', '南通', '镇江']),
        'business' => $faker->randomElement(['CMCC', 'CUCC', 'CHINANET']),
    ];
});

