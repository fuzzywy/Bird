<?php

//use Faker\Generator as Faker;

$factory->define(\App\Models\City::class, function () {
	$faker = Faker\Factory::create('zh_CN');
    return [
    	'connName' => $faker->name,
        'cityChinese' => $faker->city
    ];
});
