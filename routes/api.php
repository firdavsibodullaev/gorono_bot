<?php

use App\Http\Controllers\Telegram\TelegramController;
use Illuminate\Support\Facades\Route;

Route::get('telegram-bot-connect', TelegramController::class)->name('telegram-bot-connect');
