<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Presentation;

class PresentationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Presentation::factory(50)->create();
    }
}
