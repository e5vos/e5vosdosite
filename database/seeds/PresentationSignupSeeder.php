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
        foreach (\App\Presentation::all() as $presentation) {
            $presentation->fillUp();
        }
    }
}
