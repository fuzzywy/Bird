<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Databaseconns::class, function (Faker $faker) {
    return [
        'connName' => $faker->name,
        'cityChinese' => $faker->randomElement(['荆州', '武汉', '咸宁']),
        'host' => $faker->randomElement(['10.30.224.148', '10.31.54.148', '10.25.227.100']),
        'port' => "2640",
        'dbName' => 'dwhdb',
        'userName' => 'dcbo',
        'password' => 'dcbo',
        'subNetwork' => $faker->word,
        'subNetworkFdd' => $faker->word,
        'subNetworkNbiot' => $faker->word
    ];
});
