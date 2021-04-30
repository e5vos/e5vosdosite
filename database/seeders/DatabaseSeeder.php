<?php
namespace Database\Seeders;
use App\BonusPoints;
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
        $this->call(BonusPointsSeeder::class);
        $this->call(LocationsSeeder::class);
        //$this->call(PasswordResetsSeeder::class);
        $this->call(PermissionsSeeder::class);
        $this->call(PresentationSignupSeeder::class);
        $this->call(ScoresSeeder::class);
        $this->call(PostsSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(TeamsSeeder::class);
        $this->call(TeamMemberSeeder::class);
    }
}
