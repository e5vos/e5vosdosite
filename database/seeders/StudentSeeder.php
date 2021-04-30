<?php
namespace Database\Seeders;

use App\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Student::factory()->count(500)->create();
    }
}
