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


// Get nemjelentkezett's based on {slot}
Route::get('/e5n/students/nemjelentkezett/{slot}','E5N\PresentationController@nemjelentkezett');

Route::post('/e5n/student/presentations/','E5N\PresentationController@getSelectedPresentations');
Route::post('/e5n/presentations/signup/', 'E5N\PresentationController@signUp');
Route::post('/e5n/presentations/signup/delete/', 'E5N\PresentationController@deleteSignUp');

// Authenticate student with {diakcode} and {omcode}
Route::post('/student/auth/','StudentController@StudentAuth');

