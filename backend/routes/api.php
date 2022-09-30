<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\{
    E5N\EventController,
    Auth\AuthController,
};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Broadcast::routes(['middleware' => ['auth:sanctum']]);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('login', [AuthController::class, 'redirect'])->name('login');


//routes related to E5N events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index');
    Route::get('/events/{slot_id}', 'index');
    Route::prefix('/event')->group(function () {
        Route::post('/', 'store')->can('create', Event::class)->name('event.store');
        //Route::get('/{id}', 'show')->name('event.show');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::get('/{id}', 'show')->name('event.show');
            Route::put('/{id}', 'update')->can('update', Event::class)->name('event.update');
            Route::delete('/{id}', 'destroy')->can('destroy', Event::class)->name('event.destroy');
            Route::put('/{id}/restore', 'restore')->can('restore', Event::class)->name('event.restore');
        });
    });
});
