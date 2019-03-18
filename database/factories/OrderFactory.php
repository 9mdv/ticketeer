<?php

$factory->define(App\Order::class, function () {
    return [
        'amount' => 5200,
        'email' => 'anon@gmail.com',
        'confirmation_number' => 'ORDCONF123456',
        'card_last_four' => '1234',
    ];
});
