<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;

class LoadUniversity implements ToCollection
{
    use Importable;

    public function __construct(
        public Collection $universities
    )
    {
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection): void
    {
        $this->universities = $collection->map(function (Collection $item) {
            return [
                'name_uz' => $item[0],
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),
            ];
        });
    }
}
