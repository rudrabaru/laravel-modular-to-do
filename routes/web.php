<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RoleManagementController;
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
    
    $user = Auth::user();
    
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard.index');
    } elseif ($user->hasRole('manager')) {
        return redirect()->route('manager.dashboard.index');
    } else {
        return redirect()->route('user.dashboard.index');
    }
})->name('dashboard');

// Import module routes
require __DIR__.'/../Modules/Tasks/routes/web.php';
require __DIR__.'/../Modules/Dashboard/routes/web.php';
require __DIR__.'/../Modules/Reminders/routes/web.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notification routes
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-as-read');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-as-read');
});

// Role Management Routes (Admin only)
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/role-management', [RoleManagementController::class, 'index'])->name('admin.role-management.index');
    Route::get('/admin/role-management/create', [RoleManagementController::class, 'createRole'])->name('admin.role-management.create');
    Route::post('/admin/role-management', [RoleManagementController::class, 'storeRole'])->name('admin.role-management.store');
    Route::get('/admin/role-management/{role}/edit', [RoleManagementController::class, 'editRole'])->name('admin.role-management.edit');
    Route::put('/admin/role-management/{role}', [RoleManagementController::class, 'updateRole'])->name('admin.role-management.update');
    Route::delete('/admin/role-management/{role}', [RoleManagementController::class, 'deleteRole'])->name('admin.role-management.delete');
    Route::get('/admin/role-management/assign-roles', [RoleManagementController::class, 'assignRoles'])->name('admin.role-management.assign-roles');
    Route::put('/admin/role-management/users/{user}/roles', [RoleManagementController::class, 'updateUserRoles'])->name('admin.role-management.update-user-roles');
});

require __DIR__.'/auth.php';
