<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\VOLTE_TDD_DAY::class, function (Faker $faker) {
    return [
        'day_id' => $faker->date('Y/m/d'),
        'location' => $faker->city,
        'access' => number_format($faker->randomFloat(2, 98, 100), 2),
        'lost' => number_format($faker->randomFloat(2, 0, 0.3), 2),
        'handover' => number_format($faker->randomFloat(2, 98, 100), 2),
        'srvcc' => number_format($faker->randomFloat(2, 50, 100), 2),
        'upackagelost' => number_format($faker->randomFloat(2, 0, 1), 2),
        'dpackagelost' => number_format($faker->randomFloat(2, 0, 1), 2),
    ];
});
