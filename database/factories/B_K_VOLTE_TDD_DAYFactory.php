<?php

//use Faker\Generator as Faker;

$factory->define(\App\Models\B_K_VOLTE_TDD_DAY::class, function () {
	$faker = Faker\Factory::create('zh_CN');
    return [
        'day_id' => $faker->dateTimeThisMonth->format('Y-m-d H:i:s'),
        'province' => $faker->randomElement(['江苏', '安徽', '湖北', '四川', '广东']),
        'city' => $faker->city,
        'r_access' => number_format($faker->randomFloat(2, 98, 100), 2),
        'r_lost' => number_format($faker->randomFloat(2, 0, 0.3), 2),
        'r_handover' => number_format($faker->randomFloat(2, 98, 100), 2),
        'r_srvcc' => number_format($faker->randomFloat(2, 50, 100), 2),
        'r_u_packetlost' => number_format($faker->randomFloat(2, 0, 1), 2),
        'r_d_packetlost' => number_format($faker->randomFloat(2, 0, 1), 2),
    ];
});
