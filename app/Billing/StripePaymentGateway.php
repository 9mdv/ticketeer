<?php

namespace App\Billing;

use Stripe\Error\InvalidRequest;

class StripePaymentGateway implements PaymentGateway
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function charge($amount, $token)
    {
        try {
            \Stripe\Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $token,
            ], ['api_key' => $this->apiKey]);
        } catch (InvalidRequest $e) {
            throw new PaymentFailedException;
        }
    }

    public function getValidTestToken()
    {
        return \Stripe\Token::create([
            "card" => [
                "number" => "4242424242424242",
                "exp_month" => 3,
                "exp_year" => 2020,
                "cvc" => "314"
            ]
        ], ['api_key' => $this->apiKey])->id;
    }

    public function newChargesDuring($callback)
    {
        $latestCharge = $this->lastCharge();
        $callback($this);
        return $this->newChargesSince($latestCharge)->pluck('amount');
    }

    private function lastCharge()
    {
        return array_first(\Stripe\Charge::all(
            ['limit' => 1],
            ['api_key' => $this->apiKey]
        )['data']);
    }

    private function newChargesSince($charge = null)
    {
        $newCharges = \Stripe\Charge::all(
            ['ending_before' => $charge ? $charge->id : null],
            ['api_key' => $this->apiKey]
        )['data'];

        return collect($newCharges);
    }
}
