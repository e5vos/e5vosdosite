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

Route::get('/e5n','E5NController@home');
Route::get('/e5n/home','E5NController@home');
Route::get('/e5n/presentations','E5NController@presentations');
Route::get('/e5n/scanner','E5NController@scanner');
Route::get('/e5n/map','E5NController@map');
Route::get('/e5n/admin','E5NController@admin');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
