<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\BonusPoint;

class BonusPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BonusPoint::factory(50)->create();
    }
}
