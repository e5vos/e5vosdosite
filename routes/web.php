<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Login redirection waiter
Route::get('/home', 'HomeController@index')->name('home');

// Site admin Page
Route::get('/admin', 'Homecontroller@admin');

// Main Event viewer
Route::get('/e5n/events', 'E5N\EventController@home');

// Specific Eventpage
Route::get('/e5n/events/{day}/{event_name}', 'E5N\EventController@event');

// E5N homepage
Route::get('/e5n/home', 'E5N\E5NController@home');

// E5N homepage
Route::get('/e5n', 'E5N\E5NController@home');

// E5N map of events
Route::get('/e5n/map','E5N\E5NController@map');

// E5N orga page
Route::get('/e5n/admin','E5N\E5NController@admin');

// E5N presentation signup page
//Route::get('/e5n/presentations','E5N\PresentationController@presentations');

Route::get('/e5n/teams/','E5N\TeamController@home');

Route::get('/e5n/scanner','E5N\EventController@scanner')->middleware('auth');
Route::get('/e5n/codes','E5N\E5NController@codes');

Route::get('/e5n/presentations',function(){return view('e5n.presentations.signup');});
Route::get('/e5n/attendanceopener', function(){return view('e5n.presentations.attendancesheetopener');});
Route::get('/e5n/presentations/attendance/{prescode}',function(){return view('e5n.presentations.attendancesheet');});
Route::get('/e5n/presadmin',function(){return view('e5n.presentations.admin');});

