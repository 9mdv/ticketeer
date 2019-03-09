<?php

use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'amount' => 5200,
        'email' => 'anon@gmail.com',
    ];
});
