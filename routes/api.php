<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\{
    E5N\EventController,
    Auth\AuthController,
    E5N\SlotController,
    E5N\TeamController,
    LocationController,
    Misc\UserController,
};
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Auth\PermissionController;
use App\Http\Controllers\Admin\AdminController;
use App\Models\{
    Attendance,
    Event,
    Slot,
    Setting,
    TeamMembership,
    Team,
    User,
};
use Tightenco\Ziggy\Ziggy;
use App\Events\{
    Ping
};
use App\Http\Resources\{
    UserResource,
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


Route::get('/ziggy', fn () => response()->json(new Ziggy));

Route::get('/login', [AuthController::class, 'redirect'])->name('login');
Route::middleware(['auth:sanctum'])->patch('/e5code', [AuthController::class, 'setE5code'])->name('user.e5code');

Route::controller(UserController::class)->middleware(['auth:sanctum'])->prefix('/user')->group(function () {
    Route::get('/', function (Request $request) {
        return (new UserResource($request->user()->load('permissions')))->jsonSerialize();
    })->name('user');
    Route::get('/{userId}', 'show')->can('view', User::class)->name('users.show');
    Route::put('/{userId}', 'update')->can('update', User::class)->name('users.update');
    Route::delete('/{userId}', 'destroy')->can('delete', User::class)->name('users.destroy');
    Route::put('/{userId}/restore', 'restore')->can('restore', User::class)->name('users.restore');
});
Route::get('/users', [UserController::class, 'index'])->middleware(['auth:sanctum'])->can('viewAny', User::class)->name('users.index');
// Route::get('/users', [UserController::class, 'index'])->name('users.index');

//routes telated to e5n slots
Route::controller(SlotController::class)->prefix('/slot')->group(function () {
    Route::get('/', 'index')->name('slot.index');
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/', 'store')->can('create', Slot::class)->name('slot.store');
        Route::delete('/{slotId}', 'destroy')->can('delete', Slot::class)->name('slot.destroy');
        Route::put('/{slotId}', 'update')->can('update', Slot::class)->name('slot.update');
        Route::get('/{slotId}/free_students', 'freeStudents')->can('freeStudents', Slot::class)->name('slot.free_students');
        Route::get('/{slotId}/missing_students', 'nonAttendingStudents')->can('freeStudents', Slot::class)->name('slot.not_attending_students');
        Route::get('/{slotId}/attending_students', 'AttendingStudents')->can('freeStudents', Slot::class)->name('slot.attending_students');
    });
});

//routes related to E5N events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('events.index');
    Route::middleware(['auth:sanctum'])->post('/events', 'store')->can('create', Event::class)->name('event.store');
    Route::get('/events/{slot_id}', 'index')->name('events.slot');
    Route::get('/presentations', 'presentations')->name('events.presentations');
    Route::middleware(['auth:sanctum'])->get('/mypresentations', 'myPresentations')->name('events.mypresentations');
    Route::prefix('/event/{eventId}')->group(function () {
        Route::get('/', 'show')->name('event.show');
        Route::get('/orgas', 'orgaisers')->name('event.orgas');
        Route::middleware(['auth:sanctum'])->group(function () {
            Route::put('/', 'update')->can('update', Event::class)->name('event.update');
            Route::delete('/', 'delete')->can('delete', Event::class)->name('event.delete');
            Route::put('/restore', 'restore')->can('restore', Event::class)->name('event.restore');
            Route::put('/close', 'close_signup')->can('update', Event::class)->name('event.close_signup');
            Route::get('/participants', 'participants')->can('viewAny', Attendance::class)->name('event.participants');
            Route::post('/signup', 'signup')->can('signup', Event::class)->name('event.signup');
            Route::delete('/signup', 'unsignup')->can('unsignup', Event::class)->name('event.unsignup');
            Route::post('/attend', 'attend')->can('attend', Event::class)->name('event.attend');
        });
    });
});

//routes related to E5N teams
Route::controller(TeamController::class)->middleware(['auth:sanctum'])->group(function () {
    Route::get('/team', 'index')->can('viewAny', Team::class)->name('teams.index');
    Route::post('/team', 'store')->can('create', Team::class)->name('team.store');
    Route::get('/team/{teamCode}', 'show')->can('view', Team::class)->name('team.show');
    Route::prefix('/team/{teamCode}')->group(function () {
        Route::delete('/', 'delete')->can('delete', Team::class)->name('team.destroy');
        Route::put('/', 'update')->can('update', Team::class)->name('team.update');
        Route::put('/restore', 'restore')->can('restore', Team::class)->name('team.restore');
        Route::prefix('/members')->group(function () {
            Route::post('/', 'invite')->can('create', TeamMembership::class)->name('team.invite');
            Route::delete('/', 'kick')->can('delete', TeamMembership::class)->name('team.kick');
            Route::put('/', 'promote')->can('update', TeamMemberShip::class)->name('team.promote');
        });
    });
});

// Routes related to locations
Route::controller(LocationController::class)->prefix('locations')->group(function () {
    Route::get('/', 'index')->name('location.index');
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


Route::any('cacheclear', [AdminController::class, "cacheClear"])->name('cacheclear');

//test websocket broadcast
Route::post('/ping', function () {
    Ping::dispatch();
});


//if anything else that starts with /api/ is requested, return 404
Route::any('/{any}', function () {
    abort(404);
})->where('any', '.*');
