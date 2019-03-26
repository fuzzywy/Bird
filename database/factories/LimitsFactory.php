<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Limits::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(['GSM', 'LTE', 'NBIOT']),
        'name' => $faker->randomElement(['lost', 'handover', 'access', 'upackagelost','srvcc','dpackagelost','interfererate']),
        'operate' => $faker->randomElement(['>', '<', '!=', '=']),
        'value' => $faker->randomNumber(8),
    ];
});
