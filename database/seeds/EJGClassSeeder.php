<?php

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
        factory(App\EJGClass::class,29)->create();
    }
}
