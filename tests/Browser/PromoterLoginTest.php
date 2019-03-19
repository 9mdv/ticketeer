<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PromoterLoginTest extends DuskTestCase
{
    use RefreshDatabase;

    /** @test */
    public function logging_in_successfully()
    {
        $user = factory(User::class)->create([
            'email' => 'johnny@gmail.com',
            'password' => bcrypt('uncrackable-password'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'johnny@gmail.com')
                ->type('password', 'uncrackable-password')
                ->press('Log in')
                ->assertPathIs('/backstage/concerts');
        });
    }

    /** @test */
    public function logging_in_with_invalid_credentials()
    {
        $user = factory(User::class)->create([
            'email' => 'johnny@gmail.com',
            'password' => bcrypt('uncrackable-password'),
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('email', 'johnny@gmail.com')
                ->type('password', 'wrong-password')
                ->press('Log in')
                ->assertPathIs('/login')
                ->assertSee('credentials do not match');
        });
    }
}
