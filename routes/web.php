<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExcelController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['guest', 'throttle'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('excel-export', ExcelController::class)->name('excel.export');
});
