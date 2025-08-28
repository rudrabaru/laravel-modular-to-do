<?php

use Illuminate\Support\Facades\Route;
use Modules\Reminders\Http\Controllers\RemindersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('reminders', RemindersController::class)->names('reminders');
});
