<?php

namespace Database\Seeders;

use App\Models\BonusPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BonusPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BonusPoint::factory()->count(20)->create();
    }
}
