<?php

use App\Bookdata;
use Faker\Generator as Faker;

$factory->define(Bookdata::class, function (Faker $faker) {
    return [
        'detail' => $faker->name,
        'isbn' => $faker->unique()->isbn13,
        'title' => $faker->name,
        'volume' => $faker->name,
        'series' => $faker->name,
        'publisher' => $faker->name,
        'pubdate' => $faker->date,
        'author' => $faker->name
    ];
});
