<?php

use Carbon\Carbon;

$factory->define(App\Concert::class, function () {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'title' => 'Example Band',
        'subtitle' => 'with The Openers',
        'additional_info' => 'Some sample additional information.',
        'date' => Carbon::parse('December 18, 2019 8:00pm'),
        'venue' => 'The Example Theatre',
        'venue_address' => '123 Example Lane',
        'city' => 'Fakeville',
        'state' => 'CA',
        'zip' => '90210',
        'ticket_price' => 5250,
        'ticket_quantity' => 5,
    ];
});

$factory->state(App\Concert::class, 'published', function () {
    return [
        'published_at' => Carbon::parse('-1 week')
    ];
});

$factory->state(App\Concert::class, 'unpublished', function () {
    return [
        'published_at' => null
    ];
});
