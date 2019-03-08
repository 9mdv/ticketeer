<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Concert::class)->states('published')->create([
            'title' => 'Death Cab For Cutie',
            'subtitle' => 'with Bombay Bicycle Club and Muse',
            'venue' => 'Launchpad 39A',
            'venue_address' => '123 Sunset Drive',
            'city' => 'Laraville',
            'state' => 'CA',
            'zip' => '90210',
            'date' => Carbon::parse('December 18, 2019 8:00pm'),
            'ticket_price' => 3250,
            'additional_info' => 'For tickets, call (555) 555-5555.'
        ])->addTickets(10);
    }
}
