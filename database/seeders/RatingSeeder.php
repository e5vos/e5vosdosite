<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Rating;

class RatingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rating::factory(500)->create();
    }
}
