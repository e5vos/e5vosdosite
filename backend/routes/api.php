<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\{
    E5N\EventController,
    Auth\AuthController,
};
use App\Http\Controllers\Admin\SettingController;
use App\Models\Attendance;
use App\Models\Setting;

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
    Route::get('/events', 'index')->name('events.index');
    Route::get('/events/{slot_id}', 'index')->name('events.index');
    Route::post('/', 'store')->can('create', Event::class)->name('event.store');
    Route::prefix('/event/{id}')->group(function () {
        Route::get('/', 'show')->name('event.show');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::put('/', 'update')->can('update', Event::class)->name('event.update');
            Route::delete('/', 'destroy')->can('destroy', Event::class)->name('event.destroy');
            Route::put('/restore', 'restore')->can('restore', Event::class)->name('event.restore');
            Route::get('/attendees,')->can('viewAny', Attendance::class)->name('event.attendees');
        });
    });
});


//setting routes
Route::prefix('/setting')->middleware(['auth:sanctum'])->controller(SettingController::class)->group(
    function () {
        Route::get('/', 'index')->can('viewAny', Setting::class);
        Route::post('/', 'create')->can('create', Setting::class);
        Route::put('/{key}', 'toggle')->can('set', Setting::class);
        Route::delete('/{key}', 'destroy')->can('delete', Setting::class);
    }
);
