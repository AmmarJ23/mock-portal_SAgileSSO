<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SSOController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// SSO Routes
Route::get('login/sso', [SSOController::class, 'showLoginForm'])->name('sso.login.form');
Route::get('login/sso/callback', [SSOController::class, 'handleCallback'])->name('sso.callback');
Route::get('/dashboard', [SSOController::class, 'dashboard'])->name('dashboard');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
