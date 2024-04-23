<?php

namespace App\Console\Commands;

use App\Imports\LoadUniversity;
use App\Models\University;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class LoadUniversitiesFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-universities-from-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загрузка списка ВУЗов из Excel файла';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $universities_excel_path = storage_path('app/excel/universities.xlsx');
        if (!file_exists($universities_excel_path)) {

            $this->components->warn("Списка университетов не существует по пути: $universities_excel_path");

            return 1;
        }

        $import = (new LoadUniversity(collect()));

        $import->import($universities_excel_path);

        $this->loadToDatabase($import->universities);

        $this->components->info("Университеты загружены в базу данных");

        return 0;
    }

    private function loadToDatabase(Collection $universities): void
    {
        $universities = $universities
            ->map(function (array $university) {
                if (University::query()->where('name_uz', $university['name_uz'])->exists()) {
                    return null;
                }

                return $university;
            })
            ->filter();

        University::query()->insert($universities->toArray());
    }
}
