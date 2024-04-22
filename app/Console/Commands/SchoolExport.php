<?php

namespace App\Console\Commands;

use App\Models\Survey;
use Illuminate\Console\Command;

class SchoolExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:school-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $surveys = Survey::query()
            ->where('is_finished', '=', true)->with(['botUser.district', 'botUser.school'])
            ->get();

        $export = new \App\Exports\SchoolExport($surveys);

        $time = now()->format('Y-m-d_H-i-s');

        return $export->store("excel/survey-$time.xlsx", 'public');
    }
}
