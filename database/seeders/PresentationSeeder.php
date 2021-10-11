<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Presentation;

class PresentationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Presentation::factory()->count(50)->create();
    }
}
