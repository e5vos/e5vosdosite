<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\{
    E5N\EventController,
    Auth\AuthController,
    E5N\SlotController,
};
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\PermissionController;
use App\Models\{
    Attendance,
    Event,
    Slot,
    Setting
};
use Tightenco\Ziggy\Ziggy;
use App\Events\{
    Ping
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

Route::get('ziggy', fn () => response()->json(new Ziggy));

Route::get('login', [AuthController::class, 'redirect'])->name('login');


//routes telated to e5n slots
Route::controller(SlotController::class)->prefix('/slot')->group(function () {
    Route::get('/', 'index')->name('slot.index');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', 'store')->can('create', Slot::class)->name('slot.store');
        Route::delete('/{slot}', 'destroy')->can('delete', Slot::class)->name('slot.destroy');
        Route::put('/{slot}', 'update')->can('update', Slot::class)->name('slot.update');
    });
});

//routes related to E5N events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('events.index');
    Route::middleware(['auth:sanctum'])->post('/events', 'store')->can('create', Event::class)->name('event.store');
    Route::get('/events/{slot_id}', 'index')->name('events.slot');
    Route::get('/presentations', 'presentations')->name('events.presentations');
    Route::prefix('/event/{id}')->group(function () {
        Route::get('/', 'show')->name('event.show');
        Route::get('/orgas', 'orgaisers')->name('event.orgas');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::put('/', 'update')->can('update', Event::class)->name('event.update');
            Route::delete('/', 'delete')->can('delete', Event::class)->name('event.delete');
            Route::put('/restore', 'restore')->can('restore', Event::class)->name('event.restore');
            Route::put('/close', 'close_sigup')->can('update', Event::class)->name('event.close_signup');
            Route::get('/participants', 'participants')->can('viewAny', Attendance::class)->name('event.participants');
            Route::post('/signup', 'signup')->can('signup', Event::class)->name('event.signup');
            Route::post('/attend', 'attend')->can('attend', Event::class)->name('event.attend');
        });
    });
});

//routes related to permissions
Route::controller(PermissionController::class)->prefix('permissions/{userId}')->middleware(['auth:sanctum'])->group(function () {
    Route::prefix('/event/{eventId}')->group(function () {
        Route::post('/', 'addPermission')->can('update', Event::class)->name('permission.add_organiser');
        Route::delete('/', 'removePermission')->can('update', Event::class)->name('permission.remove_organiser');
    });
    Route::prefix('/{code}')->group(function () {
        //
    });
});


//setting routes
Route::prefix('/setting')->middleware(['auth:sanctum'])->controller(SettingController::class)->group(
    function () {
        Route::get('/', 'index')->can('viewAny', Setting::class)->name('setting.index');
        Route::post('/', 'create')->can('create', Setting::class)->name('setting.create');
        Route::put('/{key}/{value}', 'set')->can('set', Setting::class)->name('setting.set');
        Route::delete('/{key}', 'destroy')->can('delete', Setting::class)->name('setting.destroy');
    }
);

//test websocket broadcast

Route::get('/ping', function () {
    Ping::dispatch();
});
