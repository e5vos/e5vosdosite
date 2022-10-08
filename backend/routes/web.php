<?php


use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

use function PHPUnit\Framework\callback;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/asd', function () {
    return view('welcome');
});
require __DIR__.'/auth.php';




Route::get('/auth/callback', [AuthController::class, 'callback']);
