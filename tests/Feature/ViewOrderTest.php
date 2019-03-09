<?php

use Tests\TestCase;
use App\Concert;
use App\Order;
use App\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewOrderTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function user_can_view_their_order_confirmation()
    {
        // create a concert
        $concert = factory(Concert::class)->create();
        // create an order
        $order = factory(Order::class)->create([
            'confirmation_number' => 'ORDCONF123456',
            'card_last_four' => '1881',
            'amount' => 8500,
            'email' => 'khal@gmail.com',
        ]);
        // create a ticket
        $ticketA = factory(Ticket::class)->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TIX1234',
        ]);

        $ticketB = factory(Ticket::class)->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TIX123456',
        ]);

        // visit the order confirmation page
        $response = $this->get("/orders/ORDCONF123456");

        // assert we see the correct order details
        $response->assertStatus(200);
        $response->assertViewHas('order', function ($viewOrder) use ($order) {
            return $order->id === $viewOrder->id;
        });

        $response->assertSee('ORDCONF123456');
        $response->assertSee('$85.00');
        $response->assertSee('**** **** **** 1881');
        $response->assertSee('TIX1234');
        $response->assertSee('TIX123456');
        $response->assertSee('Death Cab For Cutie');
        $response->assertSee('with Bombay Bicycle Club and Muse');
        $response->assertSee('Launchpad 39A');
        $response->assertSee('123 Sunset Drive');
        $response->assertSee('Laraville, CA');
        $response->assertSee('90210');
        $response->assertSee('khal@gmail.com');
        $response->assertSee('2019-12-18 20:00');
    }
}
