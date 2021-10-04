<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


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


Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\GoogleController@callback');

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

//Settings
Route::get('/settings','HomeController@settingsView');


Route::get('/e5n/presentations','E5N\PresentationController@presentationsSignup');
Route::get('/e5n/attendanceopener', 'E5N\PresentationController@attendanceOpener');
Route::get('/e5n/presentations/attendance/{prescode}','E5N\PresentationController@attendanceViewer');
Route::get('/e5n/presadmin','E5N\PresentationController@presAdmin');

