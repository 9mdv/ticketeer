<?php

use Tests\TestCase;
use App\Billing\StripePaymentGateway;
use App\Billing\PaymentFailedException;

/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->lastCharge = $this->lastCharge();
    }

    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        $paymentGateway = $this->getPaymentGateway();

        $newCharges = $paymentGateway->newChargesDuring(function ($paymentGateway) {
            $paymentGateway->charge(2500, $paymentGateway->getValidTestToken());
        });

        $this->assertCount(1, $newCharges);
        $this->assertEquals(2500, $newCharges->sum());
    }

    /** @test */
    function charges_with_an_invalid_token_fail()
    {
        // try {
        //     $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
        //     $paymentGateway->charge(2500, 'invalid-payment-token');
        // } catch (PaymentFailedException $e) {
        //     $this->assertCount(0, $this->newCharges());
        //     return;
        // }

        // $this->fail('Charging with an invalid payment token did not throw a PaymentFailedException.');

        $paymentGateway = new StripePaymentGateway(config('services.stripe.secret'));
        $result = $paymentGateway->charge(2500, 'invalid-payment-token');
        $this->assertFalse($result);
    }

    private function lastCharge()
    {
        return \Stripe\Charge::all(
            ['limit' => 1],
            ['api_key' => config('services.stripe.secret')]
        )['data'][0];
    }

    private function newCharges()
    {
        return \Stripe\Charge::all(
            [
                'limit' => 1,
                'ending_before' => $this->lastCharge->id ?? null,
            ],
            ['api_key' => config('services.stripe.secret')]
        )['data'];
    }

    private function validToken()
    {
        return \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 3,
                "exp_year" => 2020,
                "cvc" => "314"
            ]
        ], ['api_key' => config('services.stripe.secret')])->id;
    }

    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }
}
