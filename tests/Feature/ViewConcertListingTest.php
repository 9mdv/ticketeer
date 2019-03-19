<?php

namespace Tests\Feature;

use App\Concert;
use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewConcertListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_view_published_concert_listing()
    {
        // Arrange
        // Create a concert
        $concert = factory(Concert::class)->states('published')->create([
            'title' => 'Death Cab For Cutie',
            'subtitle' => 'with Bombay Bicycle Club and Muse',
            'date' => Carbon::parse('December 18, 2019 8:00pm'),
            'ticket_price' => 3250,
            'venue' => 'Launchpad 39A',
            'venue_address' => '123 Sunset Drive',
            'city' => 'Laraville',
            'state' => 'CA',
            'zip' => '90210',
            'additional_info' => 'For tickets, call (555) 555-5555.'
        ]);

        // Act
        // View the concert listing
        $response = $this->get('/concerts/' . $concert->id);

        // Assert
        // See the concert details
        $response->assertStatus(200);
        $response->assertSee('Death Cab For Cutie');
        $response->assertSee('with Bombay Bicycle Club and Muse');
        $response->assertSee('December 18, 2019');
        $response->assertSee('8:00pm');
        $response->assertSee('32.50');
        $response->assertSee('Launchpad 39A');
        $response->assertSee('123 Sunset Drive');
        $response->assertSee('Laraville, CA 90210');
        $response->assertSee('For tickets, call (555) 555-5555.');
    }

    /** @test */
    function user_cannot_view_unpublished_concert_listing()
    {
        $concert = factory(Concert::class)->states('unpublished')->create();

        $response = $this->get('/concerts/' . $concert->id);

        $response->assertStatus(404);
    }
}
