<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PromoterLoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function logging_in_with_valid_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'johnny@gmail.com',
            'password' => bcrypt('uncrackable-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'johnny@gmail.com',
            'password' => 'uncrackable-password',
        ]);

        $response->assertRedirect('/backstage/concerts');
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->is($user));
    }

    /** @test */
    function logging_in_with_invalid_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'johnny@gmail.com',
            'password' => bcrypt('uncrackable-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'johnny@gmail.com',
            'password' => 'attempt-at-hacking-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(Auth::check());
    }

    /** @test */
    function logging_in_with_nonexistent_account()
    {
        $response = $this->post('/login', [
            'email' => 'anon@gmail.com',
            'password' => 'fluffy123456',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors(['email']);
        $this->assertFalse(Auth::check());
    }
}
