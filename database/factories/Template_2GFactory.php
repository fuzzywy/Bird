<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Template_2G::class, function (Faker $faker) {
    return [
        'templateName' => $faker->text(10),
        'elementId' => implode(',', $faker->randomElements([1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 3)),
        'description' => $faker->text(10),
        'user' => $faker->randomElement(['usr1','usr2','usr3','usr4']),
    ];
});
