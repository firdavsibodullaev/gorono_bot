<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class LoadSchool implements ToCollection
{
    use Importable;

    public function __construct(
        public Collection $schools
    )
    {
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection): void
    {
        $this->schools = $collection->map(function (Collection $item) {
            return [
                'school' => [
                    'name_uz' => $item[1],
                    'name_ru' => $item[3],
                ],
                'district' => [
                    'name_uz' => $item[2],
                    'name_ru' => $item[4],
                ]
            ];
        });
    }
}
