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
Route::post('/e5n/presentations/attendance/{code}/{signupId}','E5N\PresentationController@toggleAttendance');


// Get Selected Presentation Data
Route::get('/e5n/presentations/selected/{diakkod}/{omkod}/{slot}','E5N\PresentationController@selected');

// List Events
Route::get('/e5n/events/{weight}', 'E5N\EventController@weight');
// Get Ongoing events
Route::get('/e5n/events/ongoing', 'E5N\EventController@ongoing');
// Specific Event data
Route::get('/e5n/{day}/{event_name}', 'E5N\EventController@event_data');



// Get no signups based on {slot}
Route::get('/e5n/students/nemjelentkezett/{slot}','E5N\PresentationController@nemjelentkezett');
// Get the presentation list
Route::post('/e5n/student/presentations/','E5N\PresentationController@getSelectedPresentations');
// Signup a student to a presentation
Route::post('/e5n/presentations/signup/', 'E5N\PresentationController@signUp');
// revoke a students appliance to a presentation
Route::post('/e5n/presentations/signup/delete/', 'E5N\PresentationController@deleteSignUp');
// automaticly fill up a presentations capacity
Route::post('/e5n/presadmin/fillup/', 'E5N\PresentationController@fillUpPresentation');
Route::post('/e5n/presadmin/fillupall/', 'E5N\PresentationController@fillUpAllPresentation');

// Authenticate student with {diakcode} and {omcode}
Route::post('/student/auth/','StudentController@StudentAuth');


// get all settings
Route::get('/settings','HomeController@settings');
// change a setting
Route::post('/setting','HomeController@setSetting');
