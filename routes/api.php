<?php

use App\Http\Controllers\E5N\E5NController;
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
// Get Presentation Data
Route::get('/e5n/presentation/{id}','E5N\PresentationController@show');
// Create Presentation
Route::post('/e5n/presentations','E5N\PresentationController@store');
// Delete Presentation
Route::delete('/e5n/presentations','E5N\PresentationController@destroy');
//Get Presentation Attendance Data
Route::get('/e5n/presentations/attendance/{code}','E5N\PresentationController@attendanceSheet');
// Set Presentation Attendance Data
Route::get('/e5n/presentations/attendance/{code}/{id}','E5N\PresentationController@toggleAttendance');
// Get Selected Presentation Data
Route::get('/e5n/presentations/selected/{diakkod}/{omkod}/{slot}','E5N\PresentationController@selected');

// List Events
Route::get('/e5n/events/{weight}', 'E5N\EventController@weight');
// Get Ongoing events
Route::get('/e5n/events/ongoing', 'E5N\EventController@ongoing');
// Specific Event data
Route::get('/e5n/{day}/{event_name}', 'E5N\EventController@event_data');



// Get nemjelentkezett's based on {slot}
Route::get('/e5n/students/nemjelentkezett/{slot}','E5N\PresentationController@nemjelentkezett');



// Authenticate student with {diakcode} and {omcode}
Route::get('/student/auth/{diakcode}/{omcode}','StudentController@StudentAuth');


// Get All Student Data
Route::get('/e5n/students','StudentController@students')->middleware('auth');
// Get Student Data based on {code}
Route::get('/student/{code}','StudentController@student')->middleware('auth');
// Set {{code}} student as magantanulo
Route::get('/student/{code}/magantanulo','StudentController@setMagantanulo')->middleware('auth');
