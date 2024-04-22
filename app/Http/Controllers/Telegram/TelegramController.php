<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Modules\Telegram\Facades\Request as TelegramRequest;
use App\Telegram\BotInit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        try {
            $update = TelegramRequest::getWebhookUpdates($request);

            (new BotInit($update))->index();
        } catch (Throwable $e) {
            Log::channel('daily')->error("Telegram error", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }

        return response(['success' => true]);
    }
}
