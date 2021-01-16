<?php

use Illuminate\Database\Seeder;

class PresentationSignupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\PresentationSignup::class,500)->create();
    }
}
