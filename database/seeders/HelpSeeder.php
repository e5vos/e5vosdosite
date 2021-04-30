<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Help;
class HelpSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Help::factory()->count(50)->create();
    }
}
