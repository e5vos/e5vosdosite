<?php

namespace Database\Seeders;

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
        $this->call(EJGClassSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BonusPointSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(EventSignupSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(TeamMemberSeeder::class);
        $this->call(RatingSeeder::class);
    }
}
