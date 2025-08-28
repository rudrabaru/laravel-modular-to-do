<?php

use Illuminate\Support\Facades\Route;
use Modules\Reminders\Http\Controllers\RemindersController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('reminders', RemindersController::class)->names('reminders');
});
