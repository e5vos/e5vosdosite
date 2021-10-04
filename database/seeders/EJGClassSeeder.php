<?php
namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\EJGClass;

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
