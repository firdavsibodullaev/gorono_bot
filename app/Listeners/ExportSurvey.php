<?php

namespace App\Listeners;

use App\Events\HandleExports;
use App\Exports\SchoolExport;
use App\Models\Survey;
use App\Modules\Telegram\Facades\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Storage;

class ExportSurvey
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
        $surveys = Survey::query()
            ->orderBy('id')
            ->where('is_finished', '=', true)
            ->with(['botUser.district', 'botUser.school'])
            ->get();

        $export = new SchoolExport($surveys);

        $time = now()->format('Y-m-d_H-i-s');
        $file_path = "excel/survey-$time.xlsx";

        $export->store($file_path, 'public');

        $file_full_path = Storage::disk('public')->path($file_path);

        $uploaded = new UploadedFile($file_full_path, basename($file_full_path), mime_content_type($file_full_path));

        Request::sendDocument($event->chat_id, $uploaded);

        Storage::disk('public')->delete($file_path);
    }
}
