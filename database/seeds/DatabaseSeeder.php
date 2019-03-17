<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Concert::class)->states('published')->create()->addTickets(10);

        factory(App\Invitation::class)->create();
    }
}
