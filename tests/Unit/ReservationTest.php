<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Concert;
use App\Reservation;
use App\Ticket;

class ReservationTest extends TestCase
{
    /** @test */
    function calculating_the_total_cost()
    {
        $tickets = collect([
            (object)['price' => 1200],
            (object)['price' => 1200],
            (object)['price' => 1200],
        ]);

        $reservation = new Reservation($tickets);

        $this->assertEquals(3600, $reservation->totalCost());
    }
}
