<?php

use App\Property;
use Faker\Generator as Faker;

$factory->define(Property::class, function (Faker $faker) {
    return [
        'user_id' => function() {
            return factory(App\User::class)->create()->id;
        },
        'bookdata_id' => function() {
            return factory(App\Bookdata::class)->create()->id;
        },
        'number' => mt_rand(1,10),
        'getdate' => $faker->date,
        'freememo' => $faker->name,
    ];
});
