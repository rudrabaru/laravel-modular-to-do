<?php

use Illuminate\Support\Facades\Route;
use Modules\Reminders\Http\Controllers\ReminderController;

// User reminder routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user/reminders/create', [ReminderController::class, 'create'])->name('user.reminders.create');
    Route::post('/user/reminders', [ReminderController::class, 'store'])->name('user.reminders.store');
    Route::get('/user/reminders/{reminder}', [ReminderController::class, 'show'])->name('user.reminders.show');
    Route::get('/user/reminders/{reminder}/edit', [ReminderController::class, 'edit'])->name('user.reminders.edit');
    Route::put('/user/reminders/{reminder}', [ReminderController::class, 'update'])->name('user.reminders.update');
    Route::delete('/user/reminders/{reminder}', [ReminderController::class, 'destroy'])->name('user.reminders.destroy');
});
