<?php

use App\Http\Controllers\Telegram\TelegramController;
use Illuminate\Support\Facades\Route;

Route::post('telegram-bot-connect', TelegramController::class)->name('telegram-bot-connect');
