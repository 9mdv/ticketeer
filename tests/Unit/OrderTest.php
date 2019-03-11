<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Order;
use App\Reservation;
use App\Ticket;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Billing\Charge;

class OrderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function creating_an_order_from_tickets_email_and_charge()
    {
        $charge = new Charge(['amount' => 3600, 'card_last_four' => '1234']);
        $tickets = collect([
            \Mockery::spy(Ticket::class),
            \Mockery::spy(Ticket::class),
            \Mockery::spy(Ticket::class),
        ]);

        $order = Order::forTickets($tickets, 'ned@gmail.com', $charge);

        $this->assertEquals('ned@gmail.com', $order->email);
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals('1234', $order->card_last_four);
        $tickets->each->shouldHaveReceived('claimFor', [$order]);
    }

    /** @test */
    function retreiving_an_order_by_confirmation_number()
    {
        $order = factory(Order::class)->create([
            'confirmation_number' => 'ORDCONF123456',
        ]);

        $foundOrder = Order::findByConfirmationNumber('ORDCONF123456');

        $this->assertEquals($order->id, $foundOrder->id);
    }

    /** @test */
    function retreiving_a_nonexistent_order_by_confirmation_number_throws_an_exception()
    {
        try {
            Order::findByConfirmationNumber('NONEXISTENTORDCONF123456');
        } catch (ModelNotFoundException $e) {
            return;
        }

        $this->fail('No matching order was found for specified confirmation number, but an exception was not thrown.');
    }

    /** @test */
    function converting_to_an_array()
    {
        $order = factory(Order::class)->create([
            'confirmation_number' => 'ORDERCONFIRMATION123456',
            'email' => 'jamie@example.com',
            'amount' => 6000,
        ]);
        $order->tickets()->saveMany([
            factory(Ticket::class)->create(['code' => 'TICKETCODE1']),
            factory(Ticket::class)->create(['code' => 'TICKETCODE2']),
            factory(Ticket::class)->create(['code' => 'TICKETCODE3']),
        ]);

        $result = $order->toArray();

        $this->assertEquals([
            'confirmation_number' => 'ORDERCONFIRMATION123456',
            'email' => 'jamie@example.com',
            'amount' => 6000,
            'tickets' => [
                ['code' => 'TICKETCODE1'],
                ['code' => 'TICKETCODE2'],
                ['code' => 'TICKETCODE3'],
            ],
        ], $result);
    }
}
