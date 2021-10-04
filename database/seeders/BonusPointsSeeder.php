<?php
namespace Database\Seeders;

<<<<<<< HEAD:database/seeders/PostSeeder.php
namespace Database\Seeders;

=======
use App\BonusPoints;
>>>>>>> dev:database/seeders/BonusPointsSeeder.php
use Illuminate\Database\Seeder;
use App\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
<<<<<<< HEAD:database/seeders/PostSeeder.php
        Post::factory(50)->create();
=======
        BonusPoints::factory()->count(50)->create();
>>>>>>> dev:database/seeders/BonusPointsSeeder.php
    }
}
