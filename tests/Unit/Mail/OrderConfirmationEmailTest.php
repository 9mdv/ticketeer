<?php

namespace Tests\Unit\Mail;

use App\Order;
use Tests\TestCase;
use App\Mail\OrderConfirmationEmail;

class OrderConfirmationEmailTest extends TestCase
{
    /** @test */
    function email_contains_a_link_to_the_order_confirmation_page()
    {
        $order = factory(Order::class)->make([
            'confirmation_number' => 'ORDERCONFIRMATION123456',
        ]);
        $email = new OrderConfirmationEmail($order);
        $rendered = (string)$email->render();

        $this->assertStringContainsString(url('/orders/ORDERCONFIRMATION123456'), $rendered);
    }

    /** @test */
    function email_has_a_subject()
    {
        $order = factory(Order::class)->make();
        $email = new OrderConfirmationEmail($order);

        $this->assertEquals('Your Ticketeer order', $email->build()->subject);
    }
}
