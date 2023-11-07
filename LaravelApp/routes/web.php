<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/register', [RoleController::class, 'showRegistrationForm'])->name('register');

Route::get('/user', [CurrentUserController::class,'showRegistrationForm'])->name('UserRegister');

Route::get('/admin-dashboard', function () {
    // Route accessible only to users with 'admin' role
})->middleware('auth', 'role:admin');
