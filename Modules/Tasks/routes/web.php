<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\TasksController;
use Modules\Tasks\Http\Controllers\Admin\TasksController as AdminTasksController;
use Modules\Tasks\Http\Controllers\User\TasksController as UserTasksController;

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/tasks', [AdminTasksController::class, 'index'])->name('admin.tasks.index');
    Route::get('/admin/users', [AdminTasksController::class, 'users'])->name('admin.users.index');
});

// User routes
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::resource('user/tasks', UserTasksController::class)->names('user.tasks');
});

// Legacy routes (if needed)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tasks', TasksController::class)->names('tasks');
});
