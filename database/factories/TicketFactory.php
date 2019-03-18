<?php

use Carbon\Carbon;

$factory->define(App\Ticket::class, function () {
    return [
        'concert_id' => function () {
            return factory(App\Concert::class)->create()->id;
        }
    ];
});

$factory->state(App\Ticket::class, 'reserved', function () {
    return [
        'reserved_at' => Carbon::now()
    ];
});
