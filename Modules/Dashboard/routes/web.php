<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\DashboardController;
use Modules\Dashboard\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Modules\Dashboard\Http\Controllers\User\DashboardController as UserDashboardController;

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');
});

// User routes
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard.index');
});

// Legacy routes (if needed)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('dashboards', DashboardController::class)->names('dashboard');
});
