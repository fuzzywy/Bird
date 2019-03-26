<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Databaseconn2G::class, function (Faker $faker) {
    return [
        'connName' => $faker->name,
        'cityChinese' => $faker->randomElement(['常州', '南通', '无锡', '苏州', '镇江']),
        'host' => $faker->randomElement(['10.40.56.236', '10.197.32.108', '10.197.12.236', '10.40.82.172', '10.197.128.44']),
        'port' => "2640",
        'dbName' => 'dwhdb',
        'userName' => 'dcbo',
        'password' => 'dcbo',
    ];
});
