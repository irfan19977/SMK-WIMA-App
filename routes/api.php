<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\RFIDController;

// API Routes untuk RFID (tanpa CSRF)
Route::prefix('rfid')->group(function () {
    Route::post('/detect', [RFIDController::class, 'detect']);
    Route::post('/validate-card', [RFIDController::class, 'validateCard']);
    Route::get('/clear-cache', [RFIDController::class, 'clearCache']);
    Route::get('/latest', [RFIDController::class, 'getLatest']);
    Route::post('/auto-attendance', [RFIDController::class, 'autoAttendance']);
});
