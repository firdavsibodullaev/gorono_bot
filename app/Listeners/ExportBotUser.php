<?php

namespace App\Listeners;

use App\Events\HandleExports;
use App\Exports\BotUserExport;
use App\Models\BotUser;
use App\Modules\Telegram\Facades\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class ExportBotUser
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(HandleExports $event): void
    {
        $users = BotUser::query()
            ->where('is_registered', '=', true)
            ->with(['district', 'school', 'university'])
            ->get();

        $export = new BotUserExport($users);

        $time = now()->format('Y-m-d_H-i-s');
        $file_path = "excel/oquvchi-talaba-$time.xlsx";

        $export->store($file_path, 'public');

        $file_full_path = Storage::disk('public')->path($file_path);

        $uploaded = new UploadedFile($file_full_path, basename($file_full_path), mime_content_type($file_full_path));

        Request::sendDocument($event->chat_id, $uploaded);

        Storage::disk('public')->delete($file_path);
    }
}
