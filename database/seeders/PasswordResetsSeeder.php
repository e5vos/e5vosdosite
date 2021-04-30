<?php
namespace Database\Seeders;

use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Auth\Notificatios\ResetPassword;
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
        ResetPasswordController::factory()->count(50)->create();
        factory(App\Http\Controllers\Auth\ResetPasswordController::class,50)->create();
    }
}
