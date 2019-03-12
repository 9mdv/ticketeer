<?php

namespace Tests\Feature\Backstage;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Concert;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\Assert;

class ViewConcertListTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        TestResponse::macro('data', function ($key) {
            return $this->original->getData()[$key];
        });

        Collection::macro('assertContains', function ($value) {
            Assert::assertTrue($this->contains($value), 'Failed asserting that a collection contains the specified value.');
        });

        Collection::macro('assertNotContains', function ($value) {
            Assert::assertFalse($this->contains($value), 'Failed asserting that a collection did not contain the specified value.');
        });
    }

    /** @test */
    function guests_cannot_view_a_promoters_concert_list()
    {
        $response = $this->get('/backstage/concerts');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function promoters_can_only_view_a_list_of_their_own_concerts()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();

        $concertA = factory(Concert::class)->create(['user_id' => $user->id]);
        $concertB = factory(Concert::class)->create(['user_id' => $user->id]);
        $concertC = factory(Concert::class)->create(['user_id' => $user->id]);
        $concertD = factory(Concert::class)->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get('/backstage/concerts');

        $response->assertStatus(200);
        $response->data('concerts')->assertContains($concertA);
        $response->data('concerts')->assertContains($concertB);
        $response->data('concerts')->assertContains($concertC);
        $response->data('concerts')->assertNotContains($concertD);
    }
}
