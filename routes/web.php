<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['guest', 'throttle'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
