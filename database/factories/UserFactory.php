<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name, // 名前を自動生成
        'email' => $faker->unique()->safeEmail, // emailを自動生成、ユニーク重複なし設定付き
        'email_verified_at' => now(), // verifiedの日付を現在時刻設定
        'password' => bcrypt('12345678'), // ハッシュ化パスワード
        'remember_token' => Str::random(10), // ランダム生成
    ];
});
