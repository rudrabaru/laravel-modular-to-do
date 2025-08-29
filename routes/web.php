<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('register');
});



// After login, send users to their respective dashboards
Route::get('/dashboard', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route(Auth::user()->role === 'admin' ? 'admin.dashboard.index' : 'user.dashboard.index');
})->name('dashboard');



// Import module routes
require __DIR__.'/../Modules/Tasks/routes/web.php';
require __DIR__.'/../Modules/Dashboard/routes/web.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
