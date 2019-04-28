<?php

use App\Bookdata;
use Faker\Generator as Faker;

$factory->define(Bookdata::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'detail' => $faker->name,
        'isbn' => $faker->unique()->isbn13
    ];
});
