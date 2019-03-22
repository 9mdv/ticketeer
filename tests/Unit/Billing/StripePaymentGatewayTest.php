<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use App\Billing\StripePaymentGateway;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    use PaymentGatewayContractTests;

    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }

    /** @test */
    function ninety_percent_of_the_payment_is_transferred_to_the_destination_account()
    {
        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));

        $paymentGateway->charge(5000, $paymentGateway->getValidTestToken(), config('services.stripe.test.promoter_id'));

        $lastStripeCharge = array_first(\Stripe\Charge::all([
            'limit' => 1
        ], ['api_key' => config('services.stripe.secret')])['data']);

        $this->assertEquals(5000, $lastStripeCharge['amount']);
        $this->assertEquals(config('services.stripe.test.promoter_id'), $lastStripeCharge['destination']);

        $transfer = \Stripe\Transfer::retrieve($lastStripeCharge['transfer'], ['api_key' => config('services.stripe.secret')]);
        $this->assertEquals(4500, $transfer['amount']);
    }
}
