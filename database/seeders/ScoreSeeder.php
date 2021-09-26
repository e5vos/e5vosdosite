<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Score;

class ScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Score::factory(500)->create();
    }
}
