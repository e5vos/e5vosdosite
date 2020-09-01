<?php

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
        factory(App\BonusPoints::class,50)->create();
    }
}
