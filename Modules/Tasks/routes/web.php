<?php

use Illuminate\Support\Facades\Route;
use Modules\Tasks\Http\Controllers\Admin\TasksController as AdminTasksController;
use Modules\Tasks\Http\Controllers\User\TasksController as UserTasksController;

// Admin routes
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/tasks', [AdminTasksController::class, 'index'])->name('admin.tasks.index');
    Route::get('/admin/tasks/create', [AdminTasksController::class, 'create'])->name('admin.tasks.create');
    Route::post('/admin/tasks', [AdminTasksController::class, 'store'])->name('admin.tasks.store');
    Route::get('/admin/tasks/{task}', [AdminTasksController::class, 'show'])->name('admin.tasks.show');
    Route::get('/admin/tasks/{task}/edit', [AdminTasksController::class, 'edit'])->name('admin.tasks.edit');
    Route::put('/admin/tasks/{task}', [AdminTasksController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/admin/tasks/{task}', [AdminTasksController::class, 'destroy'])->name('admin.tasks.destroy');
    Route::get('/admin/users', [AdminTasksController::class, 'users'])->name('admin.users.index');
});

// Manager routes (same as admin but with manager role and different route names)
Route::middleware(['auth', 'verified', 'role:manager'])->group(function () {
    Route::get('/manager/tasks', [AdminTasksController::class, 'index'])->name('manager.tasks.index');
    Route::get('/manager/tasks/create', [AdminTasksController::class, 'create'])->name('manager.tasks.create');
    Route::post('/manager/tasks', [AdminTasksController::class, 'store'])->name('manager.tasks.store');
    Route::get('/manager/tasks/{task}', [AdminTasksController::class, 'show'])->name('manager.tasks.show');
    Route::get('/manager/tasks/{task}/edit', [AdminTasksController::class, 'edit'])->name('manager.tasks.edit');
    Route::put('/manager/tasks/{task}', [AdminTasksController::class, 'update'])->name('manager.tasks.update');
    Route::delete('/manager/tasks/{task}', [AdminTasksController::class, 'destroy'])->name('manager.tasks.destroy');
});

// User routes
Route::middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::resource('user/tasks', UserTasksController::class)->names('user.tasks');
});
