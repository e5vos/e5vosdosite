<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(LocationSeeder::class);
        $this->call(SlotSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(TeamMembershipSeeder::class);
        $this->call(AttendanceSeeder::class);
        $this->call(RatingSeeder::class);
        $this->call(BonusPointSeeder::class);
    }
}
