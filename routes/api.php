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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



// List Presentations
Route::get('/e5n/presentations/{slot}','E5N\PresentationController@slot');

// List Presentation

Route::get('/e5n/presentation/{id}','E5N\PresentationController@show');

// Create Presentation
Route::post('/e5n/presentations','E5N\PresentationController@store');

// Delete Presentation
Route::delete('/e5n/presentations','E5N\PresentationController@destroy');

//Get Prezentation Attendance Data
Route::get('/e5n/presentations/attendance/{code}','E5N\PresentationController@attendanceSheet');

// Set Prezentation Attendance Data
Route::post('/e5n/presentations/attendance/{code}/{id}','E5N\PresentationController@toggleAttendance');

// Get Selected Presentation Data
Route::get('/e5n/presentations/selected/{diakkod}/{omkod}/{slot}','E5N\PresentationController@selected');
