<?php

namespace Database\Seeders;

use App\BonusPoint;
use App\Permission;
use App\PresentationSignup;
use App\Providers\EventServiceProvider;
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
        $this->call(PresentationsTableSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(HelpSeeder::class);
        $this->call(EJGClassSeeder::class);
        $this->call(EventSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BonusPointSeeder::class);
        $this->call(LocationSeeder::class);
        //$this->call(PasswordResetSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(PresentationSignupSeeder::class);
        $this->call(ScoreSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(TeamSeeder::class);
        $this->call(TeamMemberSeeder::class);
    }
}
