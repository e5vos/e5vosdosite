<?php
namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        Student::factory(500)->create();
    }
}
