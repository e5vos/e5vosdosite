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

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/admin', 'Homecontroller@admin');

Route::get('/e5n/home', 'E5N\EventController@home');

Route::get('/e5n/home', 'E5N\E5NController@home');

Route::get('/e5n', 'E5N\E5NController@home');

Route::get('/e5n/map','E5N\E5NController@map');

Route::get('/e5n/admin','E5N\E5NController@admin');

Route::get('/e5n/presentations','E5N\PresentationController@presentations');

Route::get('/e5n/teams/','E5N\TeamController@home');

Route::get('/e5n/scanner','E5N\EventController@scanner')->middleware('auth');

Route::get('/e5n/presentations',function(){return view('e5n.presentations.signup');});
Route::get('/e5n/attendance',function(){return view('e5n.presentations.attendancesheet');});
Route::get('/e5n/presadmin',function(){return view('e5n.presentations.admin');});

