<?php

use App\Property;
use Faker\Generator as Faker;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'bookdata_id' => 1,
        'number' => 1,
        'getdate' => $faker->date,
        'freememo' => $faker->name,
    ];
});
