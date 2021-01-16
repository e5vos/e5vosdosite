<?php

use Illuminate\Database\Seeder;

class PasswordResetsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Http\Controllers\Auth\ResetPasswordController::class,50)->create();
    }
}
