<?php

//use Faker\Generator as Faker;

$factory->define(\App\Models\B_K_LTE_TDD_ACCESS_D_TOP::class, function () {
    $faker = Faker\Factory::create('zh_CN');
    return [
        'day_id' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'hour_id' => $faker->date('H'),
        'province' => $faker->randomElement(['江苏', '安徽', '湖北', '四川', '广东']),
        'city' => $faker->city,
        'cell' => $faker->name,
        'c_access_fail' => $faker->numberBetween(0, 100),
        'c_history' => $faker->numberBetween(0, 2400),
        'c_now' => $faker->numberBetween(0, 24),
    ];
});
