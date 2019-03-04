<?php

use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Concert::class, function (Faker $faker) {
    return [
        'title' => 'The Examples',
        'subtitle' => 'with The Mini Band',
        'date' => Carbon::parse('+2 weeks'),
        'ticket_price' => 2000,
        'venue' => 'Example Venue',
        'venue_address' => '123 Sunset Avenue',
        'city' => 'Fakeville',
        'state' => 'CA',
        'zip' => '80000',
        'additional_info' => 'More info sample'
    ];
});

$factory->state(App\Concert::class, 'published', function (Faker $faker) {
    return [
        'published_at' => Carbon::parse('-1 week')
    ];
});

$factory->state(App\Concert::class, 'unpublished', function (Faker $faker) {
    return [
        'published_at' => null
    ];
});
