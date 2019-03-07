<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Concert;
use App\Reservation;
use App\Ticket;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Billing\FakePaymentGateway;

class ReservationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function calculating_the_total_cost()
    {
        $tickets = collect([
            (object)['price' => 1200],
            (object)['price' => 1200],
            (object)['price' => 1200],
        ]);

        $reservation = new Reservation($tickets, 'john@gmail.com');

        $this->assertEquals(3600, $reservation->totalCost());
    }

    /** @test */
    function retrieving_the_reservations_tickets()
    {
        $tickets = collect([
            (object)['price' => 1200],
            (object)['price' => 1200],
            (object)['price' => 1200],
        ]);

        $reservation = new Reservation($tickets, 'john@gmail.com');

        $this->assertEquals($tickets, $reservation->tickets());
    }

    /** @test */
    function retrieving_the_customers_email()
    {
        $reservation = new Reservation(collect(), 'john@gmail.com');

        $this->assertEquals('john@gmail.com', $reservation->email());
    }

    /** @test */
    function reserved_tickets_are_released_when_a_reservation_is_cancelled()
    {
        $tickets = collect([
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
            Mockery::spy(Ticket::class),
        ]);
        $reservation = new Reservation($tickets, 'john@gmail.com');

        $reservation->cancel();

        foreach ($tickets as $ticket) {
            $ticket->shouldHaveReceived('release');
        }
    }

    /** @test */
    function completing_a_reservation()
    {
        $concert = factory(Concert::class)->create(['ticket_price' => 1200]);
        $tickets = factory(Ticket::class, 3)->create(['concert_id' => $concert->id]);
        $reservation = new Reservation($tickets, 'ned@gmail.com');
        $paymentGateway = new FakePaymentGateway;

        $order = $reservation->complete($paymentGateway, $paymentGateway->getValidTestToken());

        $this->assertEquals('ned@gmail.com', $order->email);
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals(3600, $paymentGateway->totalCharges());
    }
}
