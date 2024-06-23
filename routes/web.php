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

require __DIR__.'/auth.php';
Route::get('/auth/callback', [AuthController::class, 'callback']);

Route::get('/', function () {
    return view('frontend');
});

Route::get('/{any}', function (string $any) {
    //search ../public/ for the file
    $path = base_path().'/public/'.$any;
    if (file_exists($path)) {
        return response()->file($path);
    }

    //if not found, return the frontend
    return view('frontend');
})->where('any', '.*');

// Route::any('/eloadas', function () {
//     return view('frontend');
// });

// Route::any('/eloadas/kezel', function () {
//     return view('frontend');
// });

// Route::any('/eloadas/kezel/{id}', function () {
//     return view('frontend');
// });

// Route::any('/login', function () {
//     return view('frontend');
// });

// Route::any('/logout', function () {
//     return view('frontend');
// });
