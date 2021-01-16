<?php

use Illuminate\Database\Seeder;

class PresentationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Presentation::class,50)->create();
    }
}
