<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExcelController;
use App\Models\BotUser;
use App\Models\BotUserPostMessage;
use App\Models\PostMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware(['guest', 'throttle'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('auth/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('excel-export', ExcelController::class)->name('excel.export');
});

Route::get('', function () {

    /** @var PostMessage $post */
    $post = PostMessage::query()->find(3);

    $bupm = BotUserPostMessage::query()->where('post_message_id', $post->id)->pluck('bot_user_id');

    BotUser::query()
        ->where('is_registered', true)
        ->whereKeyNot($bupm)
        ->chunk(50, function (Collection $collection) use ($post) {
            $post->botUsers()->sync($collection->pluck('id'));
        });
});
