<?php

use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Concert::class, function (Faker $faker) {
    return [
        'title' => 'Death Cab For Cutie',
        'subtitle' => 'with Bombay Bicycle Club and Muse',
        'date' => Carbon::parse('December 18, 2019 8:00pm'),
        'ticket_price' => 3250,
        'venue' => 'Launchpad 39A',
        'venue_address' => '123 Sunset Drive',
        'city' => 'Laraville',
        'state' => 'CA',
        'zip' => '90210',
        'additional_info' => 'For tickets, call (555) 555-5555.'
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
