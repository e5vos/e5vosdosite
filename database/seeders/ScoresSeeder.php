<?php
namespace Database\Seeders;

use App\Score;
use Illuminate\Database\Seeder;

class ScoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Score::factory()->count(500)->create();
    }
}
