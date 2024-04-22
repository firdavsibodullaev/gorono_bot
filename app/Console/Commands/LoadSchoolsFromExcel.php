<?php

namespace App\Console\Commands;

use App\Imports\LoadSchool;
use App\Models\District;
use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class LoadSchoolsFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:load-schools-from-excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $schools_excel_path = storage_path('app/excel/schools.xlsx');
        if (!file_exists($schools_excel_path)) {

            $this->components->warn("Списка школ не существует по пути: $schools_excel_path");

            return 1;
        }

        $import = (new LoadSchool(collect()));

        $import->import($schools_excel_path);

        $this->loadToDatabase($import->schools);

        $this->components->info("Школы загружены в базу данных");

        return 0;
    }

    private function loadToDatabase(Collection $schools)
    {
        $schools_result = collect();
        $schools->each(function (array $school_item) use (&$schools_result) {
            $school_array = $school_item['school'];
            $district_array = $school_item['district'];

            /** @var District $district */
            $district = District::query()->firstOrCreate(['name_uz' => $district_array['name_uz']], $district_array);

            if (
                School::query()->where('district_id', $district->id)
                    ->where('name_uz', $school_array['name_uz'])
                    ->exists()
            ) {
                return;
            }

            $schools_result->push([
                'district_id' => $district->id,
                'created_at' => now(),
                'updated_at' => now(),
                ...$school_array
            ]);
        });

        School::query()->insert($schools_result->toArray());
    }
}
