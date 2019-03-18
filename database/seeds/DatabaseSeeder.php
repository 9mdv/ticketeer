<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $gateway = new \App\Billing\FakePaymentGateway;

        $user = factory(App\User::class)->create([
            'email' => "zarya@gmail.com",
            'password' => bcrypt('password'),
        ]);

        $concert = \ConcertFactory::createPublished([
            'user_id' => $user->id,
            'title' => "Death Cab For Cutie",
            'subtitle' => "with Bombay Bicycle Club and TV on the Radio",
            'additional_info' => "This concert is 19+.",
            'venue' => "Launchpad 39A",
            'venue_address' => "123 Sunset Drive",
            'city' => "Laraville",
            'state' => "CA",
            'zip' => "90210",
            'date' => Carbon::today()->addMonths(3)->hour(20),
            'ticket_price' => 3250,
            'ticket_quantity' => 250,
        ]);

        foreach (range(1, 50) as $i) {
            Carbon::setTestNow(Carbon::instance($faker->dateTimeBetween('-2 months')));

            $concert->reserveTickets(rand(1, 4), $faker->safeEmail)
                ->complete($gateway, $gateway->getValidTestToken($faker->creditCardNumber), 'test_acct_1234');
        }

        Carbon::setTestNow();

        factory(App\Concert::class)->create([
            'user_id' => $user->id,
            'title' => "Slayer",
            'subtitle' => "with Forbidden and Testament",
            'additional_info' => null,
            'venue' => "The Rock Pile",
            'venue_address' => "55 Sample Blvd",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "19276",
            'date' => Carbon::today()->addMonths(6)->hour(19),
            'ticket_price' => 5500,
            'ticket_quantity' => 10,
        ]);
    }
}
