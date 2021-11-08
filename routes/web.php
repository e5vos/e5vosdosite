<?php

use App\Http\Controllers\E5N\TeamController;
use Google\Service\AndroidPublisher\UserComment;
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

Route::get('/','WelcomeController@index')->name('index');

Route::get('/logout', 'Auth\AuthController@logout' )->name("logout");
Route::get('/login','Auth\AuthController@login')->name('login');
Route::post('/logout', 'Auth\AuthController@logout' );

Route::get('auth/{provider?}', 'Auth\AuthController@redirect')->name("auth");
Route::get('auth/{provider}/callback', 'Auth\AuthController@callback');

Route::resource('user', \Auth\UserController::class, ['only' => ['index','edit','update','destroy']]);


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

Route::put('e5n/admin/presentation/{presentation}/attendance','E5N\Admin\PresentationAdminController@setAttendance');



Route::get('/e5n/scanner','E5N\EventController@scanner')->middleware('auth');
Route::get('/e5n/codes','E5N\E5NController@codes');

//Settings
Route::get('/settings','HomeController@settingsView');


Route::resource('e5n/event', \E5N\EventController::class);
Route::resource('e5n/team',\E5N\TeamController::class);
Route::resource('e5n/presentation', \E5N\PresentationController::class, ['only' => ['index'] ]);
Route::resource('e5n/eventsignup', \E5N\EventSignupController::class);
Route::resource('e5n/admin/presentation',\E5N\Admin\PresentationAdminController::class, ["as" => "admin"]);


Route::post('e5n/team/{team}/member','E5N\TeamController@manageMember')->name('team.member.manage');

Route::post('e5n/team/{team}/leave','E5N\TeamController@leave')->name('team.member.leave');


Route::delete('e5n/team','E5N\TeamController@delete')->name('team.delete');


Route::get('/qr/{code}', 'WelcomeController@qr')->name("qr.code");


Route::post('/e5n/event/{event_code}/restore', 'E5N\EventController@restore')->name('event.restore');

Route::post('/e5n/event/{event_code}/organisers','E5N\EventController@addOrganiser')->name('event.organisers.add');
Route::delete('/e5n/event/{event_code}/organisers','E5N\EventController@removeOrganiser')->name('event.organisers.remove');


