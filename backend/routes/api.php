<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


//routes related to E5N events
Route::get('/events', [E5N\EventController::class, 'index']);
Route::get('/events/{slot_id}', [E5N\EventController::class, 'index']);
Route::get('/event/{id}', [E5N\EventController::class, 'show']);
Route::post('/event', [E5N\EventController::class, 'store']);
Route::put('/event/{id}', [E5N\EventController::class, 'update']);
Route::delete('/event/{id}', [E5N\EventController::class, 'destroy']);
Route::get('/event/{id}/restore', [E5N\EventController::class, 'restore']);
