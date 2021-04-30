<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Location;
class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Location::factory()->count(40)->create();
    }
}
