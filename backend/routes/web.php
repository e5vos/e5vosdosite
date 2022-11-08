<?php


use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;


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

require __DIR__ . '/auth.php';
Route::get('/auth/callback', [AuthController::class, 'callback']);

Route::get('/{any}', function ($any) {
    $any;
    return view('frontend');
});
