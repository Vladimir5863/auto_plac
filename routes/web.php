<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Home route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Logout route (accessible only to authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
