<?php
<<<<<<< HEAD:database/seeders/HelpSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Help;

=======
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Help;
>>>>>>> dev:database/seeds/HelpSeeder.php
class HelpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
<<<<<<< HEAD:database/seeders/HelpSeeder.php
        Help::factory(50)->create();
=======
        Help::factory()->count(50)->create();
>>>>>>> dev:database/seeds/HelpSeeder.php
    }
}
