<?php
namespace Database\Seeders;

use App\BonusPoints;
use Illuminate\Database\Seeder;

class BonusPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BonusPoints::factory()->count(50)->create();
    }
}
