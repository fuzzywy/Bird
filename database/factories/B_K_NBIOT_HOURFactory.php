<?php

//use Faker\Generator as Faker;

$factory->define(\App\Models\B_K_NBIOT_HOUR::class, function () {
    $faker = Faker\Factory::create('zh_CN');
    return [
        'day_id' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'hour_id' => $faker->date('H'),
        'province' => $faker->randomElement(['江苏', '安徽', '湖北', '四川', '广东']),
        'city' => $faker->city,
        'r_access' => number_format($faker->randomFloat(2, 98, 100), 2),
        'r_lost' => number_format($faker->randomFloat(2, 0, 0.3), 2),
        'r_u_floor' => $faker->randomElement(['-100', '-95', '-48']),
    ];
});
