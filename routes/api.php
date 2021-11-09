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
Route::get('/e5n/presentations/{slot}','E5N\EventController@presentationSlot');
// Get Presentation Data
Route::get('/e5n/presentation/{id}','E5N\EventController@show');

// Get Ongoing events
Route::get('/e5n/events/ongoing', 'E5N\EventController@ongoing');

// Get Specific Event(excl. Presentations)
Route::get('e5n/event/{event_code}', 'E5N\EventController@event_data');
Route::put('e5n/event/{event_code}/rate','E5N\EventController@rate');

// Get the presentation list
Route::get('/e5n/student/presentations/','E5N\EventSignupController@getSelectedPresentations') ;

// Signup a student to a presentation
Route::post('/e5n/student/presentations/', 'E5N\EventSignupController@signUp');
// get all settings
Route::get('/settings','HomeController@settings');
// change a setting
Route::post('/setting','HomeController@setSetting');
