<?php
namespace Database\Seeders;

use App\EJGClass;
use Illuminate\Database\Seeder;

class EJGClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EJGClass::factory()->count(20)->create();
    }
}
