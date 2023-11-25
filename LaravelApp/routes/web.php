<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Host\EventController;

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


Route::middleware(['auth', 'role:host'])->name('host.')->prefix('host')->group(function () {
    Route::get('/', [IndexController::class,'index'])->name('index');
    Route::get('/multi-step-form', [EventController::class, 'create'])->name('multi-step-form');
    Route::resource('/manage-events', EventController::class);
    Route::get('/manage-events/edit/{event}', [EventController::class, 'edit'])->name('manage-events.edit');
    Route::delete('/manage-events/events/{event}', [EventController::class, 'destroy'])->name('manage-events.destroy');

});
Route::middleware(['auth', 'role:customer'])->name('customer.')->prefix('customer')->group(function () {
    Route::get('/', [IndexController::class,'customerIndex'])->name('customerIndex');

});
