<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\HashidsTicketCodeGenerator;
use App\Ticket;

class HashidsTicketCodeGeneratorTest extends TestCase
{
    /** @test */
    function ticket_codes_are_at_least_6_characters_long()
    {
        $tickerCodeGenerator = new HashidsTicketCodeGenerator('testsalt1');

        $code = $tickerCodeGenerator->generateFor(new Ticket(['id' => 1]));

        $this->assertTrue(strlen($code) >= 6);
    }

    /** @test */
    function ticket_codes_can_only_contain_uppercase_letters()
    {
        $tickerCodeGenerator = new HashidsTicketCodeGenerator('testsalt1');

        $code = $tickerCodeGenerator->generateFor(new Ticket(['id' => 1]));

        $this->assertRegExp('/^[A-Z]+$/', $code);
    }

    /** @test */
    function ticket_code_for_the_same_ticket_id_are_the_same()
    {
        $tickerCodeGenerator = new HashidsTicketCodeGenerator('testsalt1');

        $code1 = $tickerCodeGenerator->generateFor(new Ticket(['id' => 1]));
        $code2 = $tickerCodeGenerator->generateFor(new Ticket(['id' => 1]));

        $this->assertEquals($code1, $code2);
    }

    /** @test */
    function ticket_code_for_different_ticket_ids_are_different()
    {
        $tickerCodeGenerator = new HashidsTicketCodeGenerator('testsalt1');

        $code1 = $tickerCodeGenerator->generateFor(new Ticket(['id' => 1]));
        $code2 = $tickerCodeGenerator->generateFor(new Ticket(['id' => 2]));

        $this->assertNotEquals($code1, $code2);
    }

    /** @test */
    function ticket_codes_generated_with_different_salts_are_different()
    {
        $tickerCodeGenerator1 = new HashidsTicketCodeGenerator('testsalt1');
        $tickerCodeGenerator2 = new HashidsTicketCodeGenerator('testsalt2');

        $code1 = $tickerCodeGenerator1->generateFor(new Ticket(['id' => 1]));
        $code2 = $tickerCodeGenerator2->generateFor(new Ticket(['id' => 1]));

        $this->assertNotEquals($code1, $code2);
    }
}
