<?php

use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'amount' => 5200,
        'email' => 'anon@gmail.com',
        'confirmation_number' => 'ORDCONF123456',
        'card_last_four' => '1234',
    ];
});
