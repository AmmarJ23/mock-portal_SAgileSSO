<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
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
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// SSO Routes
Route::get('/login/sso', [SSOController::class, 'showLoginForm'])->name('sso.login.form');
Route::get('/login/sso/callback', [SSOController::class, 'handleCallback'])->name('sso.callback');
Route::get('/dashboard', [SSOController::class, 'dashboard'])->name('dashboard');
Route::post('/sso/sync', [App\Http\Controllers\Auth\SSOController::class, 'syncWithSAgile'])->name('sso.sync');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/lecturer-student-assignments', [App\Http\Controllers\LecturerStudentAssignmentController::class, 'index'])
    ->name('lecturer.student.assignments')
    ->middleware('auth');

Route::post('/lecturer-student-assignments', [App\Http\Controllers\LecturerStudentAssignmentController::class, 'store'])
    ->name('lecturer.student.assignments.store')
    ->middleware('auth');
