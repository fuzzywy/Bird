<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Networks::class, function (Faker $faker) {
    return [
        'date_id' => $faker->dateTimeThisYear()->format('Y/m/d'),
        'hour_id' => $faker->date('H'),
        '无线接通率' => number_format($faker->randomFloat(2, 98, 100), 2),
        '无线掉线率' => number_format($faker->randomFloat(2, 0, 0.3), 2),
        '切换成功率' => number_format($faker->randomFloat(2, 98, 100), 2),
      	'created_at' => $faker->dateTimeThisYear
    ];
});
