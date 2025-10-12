<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;

// Home route - show vehicles for unregistered users
Route::get('/', [VehicleController::class, 'index'])->name('home');

// Vehicle search routes (public)
Route::get('/search', [VehicleController::class, 'search'])->name('vehicles.search');
Route::get('/vehicles', [VehicleController::class, 'index'])->name('vehicles.index');
Route::get('/vehicles/{id}', [VehicleController::class, 'show'])->name('vehicles.show');
Route::get('/vehicles/models/{brand}', [VehicleController::class, 'getModels'])->name('vehicles.models');

// Serbian-named public vehicle route aliases
Route::get('/pretraga', [VehicleController::class, 'search'])->name('search');
Route::get('/vozila', [VehicleController::class, 'index'])->name('vozila.index');
Route::get('/vozila/{id}', [VehicleController::class, 'show'])->name('vozila.show');
Route::get('/vozila/modeli/{brand}', [VehicleController::class, 'getModels'])->name('vozila.models');
Route::get('/vozila/pretraga', [VehicleController::class, 'search'])->name('vozila.search');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register routes
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    // Logout route
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard route (restrict to admin only)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('admin')->name('dashboard');

    // Ad management routes
    // Define specific routes BEFORE resource to avoid being captured by ads.show
    Route::get('/ads/featured', [AdController::class, 'featured'])->name('ads.featured');
    Route::resource('ads', AdController::class);

    // Oglasi (Serbian) routes
    // Define specific route BEFORE resource to avoid being captured by oglasi.show
    Route::get('/oglasi/istaknuti', [AdController::class, 'featured'])->name('oglasi.featured');
    Route::resource('oglasi', AdController::class)->names([
        'index' => 'oglasi.index',
        'create' => 'oglasi.create',
        'store' => 'oglasi.store',
        'show' => 'oglasi.show',
        'edit' => 'oglasi.edit',
        'update' => 'oglasi.update',
        'destroy' => 'oglasi.destroy',
    ]);

    // Vozila (Serbian) create/store aliases mapped to AdController
    Route::get('/vozila/create', [AdController::class, 'create'])->name('vozila.create');
    Route::post('/vozila', [AdController::class, 'store'])->name('vozila.store');

    // Purchase vehicle (ad)
    Route::get('/vehicles/{id}/purchase', [PaymentController::class, 'createPurchase'])->name('vehicles.purchase.create');
    Route::post('/vehicles/{id}/purchase', [PaymentController::class, 'purchase'])->name('vehicles.purchase');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/ads', [ProfileController::class, 'myAds'])->name('profile.my-ads');
    Route::get('/profile/payments', [ProfileController::class, 'myPayments'])->name('profile.my-payments');
    Route::get('/profile/purchases', [ProfileController::class, 'purchases'])->name('profile.purchases');

    // Serbian-named profile aliases
    Route::get('/profil', [ProfileController::class, 'index'])->name('profile');
    Route::get('/podesavanja', [ProfileController::class, 'edit'])->name('settings');
    Route::get('/profil/oglasi', [ProfileController::class, 'myAds'])->name('oglasi.my');
    Route::get('/profil/kupljena-vozila', [ProfileController::class, 'purchases'])->name('profil.purchases');

    // Payment routes (define specific before resource)
    Route::get('/payments/my', [PaymentController::class, 'myPayments'])->name('payments.my');
    Route::resource('payments', PaymentController::class);
    Route::get('/ads/{ad}/payment', [PaymentController::class, 'createForAd'])->name('ads.payment.create');
    Route::post('/ads/{ad}/payment', [PaymentController::class, 'processForAd'])->name('ads.payment.process');

    // Uplate (Serbian) routes (define specific before resource)
    Route::get('/uplate/moje', [PaymentController::class, 'myPayments'])->name('uplate.my');
    Route::resource('uplate', PaymentController::class)->names([
        'index' => 'uplate.index',
        'create' => 'uplate.create',
        'store' => 'uplate.store',
        'show' => 'uplate.show',
        'edit' => 'uplate.edit',
        'update' => 'uplate.update',
        'destroy' => 'uplate.destroy',
    ]);

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('users.show');
        Route::post('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
        Route::post('/users/{id}/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
        Route::get('/ads', [AdminController::class, 'ads'])->name('ads');
        Route::post('/ads/{id}/activate', [AdminController::class, 'activateAd'])->name('ads.activate');
        Route::delete('/ads/{id}', [AdminController::class, 'deleteAd'])->name('ads.delete');
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
        Route::get('/reports/create', [AdminController::class, 'createReport'])->name('reports.create');
        Route::post('/reports', [AdminController::class, 'storeReport'])->name('reports.store');
        Route::get('/reports/{id}', [AdminController::class, 'showReport'])->name('reports.show');
        Route::delete('/reports/{id}', [AdminController::class, 'deleteReport'])->name('reports.delete');
        Route::get('/stats/monthly', [AdminController::class, 'monthlyStats'])->name('stats.monthly');
        Route::get('/stats/quarterly', [AdminController::class, 'quarterlyStats'])->name('stats.quarterly');
        Route::get('/stats/yearly', [AdminController::class, 'yearlyStats'])->name('stats.yearly');
    });

    // Serbian-named admin reports and stats aliases (protected by admin middleware)
    Route::middleware('admin')->group(function () {
        Route::get('/izvestaji', [AdminController::class, 'reports'])->name('izvestaji.index');
        Route::get('/izvestaji/kreiraj', [AdminController::class, 'createReport'])->name('izvestaji.create');
        Route::post('/izvestaji', [AdminController::class, 'storeReport'])->name('izvestaji.store');
        Route::get('/izvestaji/{id}', [AdminController::class, 'showReport'])->name('izvestaji.show');
        Route::get('/izvestaji/mesecni', [AdminController::class, 'monthlyStats'])->name('izvestaji.monthly');
    });
});
