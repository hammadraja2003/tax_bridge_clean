<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BusinessController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\DatabaseController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::middleware(['web'])->prefix('admin')->name('admin.')->group(function () {
    // Authentication
    Route::get('admin-login', [LoginController::class, 'showLoginForm'])->name('admin_login');
    Route::post('admin-login', [LoginController::class, 'adminLogin'])->name('admin_login.submit');
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('admin-dashboard', [DashboardController::class, 'index'])->name('admin_dashboard');

        //On barding New business
        Route::get('configuration', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('configuration', [RegisteredUserController::class, 'store'])->middleware('throttle:5,1');

        // Logout
        Route::post('admin-logout', LogoutController::class)->name('admin_logout');

        // Business Management
        Route::get('businesses', [BusinessController::class, 'index'])->name('businesses.index');
        Route::get('businesses/{id}', [BusinessController::class, 'show'])->name('businesses.show');

        // Log Management
        Route::get('logs', [LogController::class, 'show'])->name('logs.show');
        Route::get('logs/clear', [LogController::class, 'clear'])->name('logs.clear');

        // Database Utilities
        Route::get('db/clone', [DatabaseController::class, 'showCloneForm'])->name('db.clone.form');
        Route::post('db/clone', [DatabaseController::class, 'clone'])->name('db.clone');
    });
});


// Log Management
Route::get('logs', [LogController::class, 'show'])->name('logs.show');
Route::get('logs/clear', [LogController::class, 'clear'])->name('logs.clear');
Route::get('logs/dashboard', [LogController::class, 'dashboard'])->name('logs.dashboard');
