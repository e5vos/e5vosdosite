<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

require __DIR__.'/auth.php';

// Legacy popup-based OAuth callback (keeps React/API token flow intact).
Route::get('/auth/callback', [AuthController::class, 'callback']);

// Session-based OAuth callback for the Livewire web flow.
Route::get('/auth/web-callback', [AuthController::class, 'webCallback']);

// Public pages
Volt::route('/', 'pages.home')->name('home');
Volt::route('/privacypolicy', 'pages.privacypolicy')->name('privacypolicy');
Volt::route('/esemeny', 'pages.esemeny.index')->name('esemeny');
Volt::route('/esemeny/{eventid}', 'pages.esemeny.show')->name('esemeny.show');
Volt::route('/sav', 'pages.sav.index')->name('sav');
Volt::route('/csapat', 'pages.csapat.index')->name('csapat');

// Auth pages (redirect to /eloadas if already logged in — handled in component mount)
Volt::route('/login', 'pages.login')->name('login');
Volt::route('/logout', 'pages.logout')->name('logout');

// Auth required
Route::middleware('auth')->group(function () {
    Volt::route('/studentcode', 'pages.studentcode')->name('studentcode');
    Volt::route('/esemeny/uj', 'pages.esemeny.create')->name('esemeny.create');
    Volt::route('/esemeny/{eventid}/kezel/szerkeszt', 'pages.esemeny.edit')->name('esemeny.edit');
    Volt::route('/esemeny/{eventid}/kezel/scanner', 'pages.esemeny.scanner')->name('esemeny.scanner');
    Volt::route('/csapat/{teamcode}', 'pages.csapat.show')->name('csapat.show');
    Volt::route('/felhasznalo/{userId}', 'pages.felhasznalo.show')->name('felhasznalo.show');
    Volt::route('/eloadas/kezel', 'pages.eloadas.manage')->name('eloadas.manage');
    Volt::route('/eloadas/kezel/{eventid}', 'pages.eloadas.attendance')->name('eloadas.attendance');
    Volt::route('/eloadas/kezel/{eventid}/scanner', 'pages.eloadas.scanner')->name('eloadas.scanner');
});

// Auth + e5code required
Route::middleware(['auth', 'has.e5code'])->group(function () {
    Volt::route('/eloadas', 'pages.eloadas.index')->name('eloadas');
});

// Admin only
Route::middleware(['auth', 'permission:ADM'])->group(function () {
    Volt::route('/admin', 'pages.admin.index')->name('admin');
    Volt::route('/admin/sav', 'pages.admin.sav.index')->name('admin.sav');
    Volt::route('/admin/sav/uj', 'pages.admin.sav.create')->name('admin.sav.create');
    Volt::route('/admin/sav/{slotid}/kezel', 'pages.admin.sav.edit')->name('admin.sav.edit');
    Volt::route('/admin/jogosultsagok', 'pages.admin.permissions')->name('admin.permissions');
});
