<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Kpiformula::class, function (Faker $faker) {
    return [
        'id' => $faker->unique()->randomNumber(8),
        'kpiName' => $faker->text(10),
        'user' => $faker->randomElement(['usr1', 'usr2', 'usr3', 'usr4']),
        'kpiFormula' => $faker->text(10),
        'kpiPrecision' => 2
    ];
});
