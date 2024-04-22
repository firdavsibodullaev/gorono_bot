<?php

namespace App\Exports;

use App\Models\Survey;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class SchoolExport implements FromView
{
    use Exportable;

    private Collection $surveys;

    public function __construct(Collection $collection)
    {
        $collection->ensure(Survey::class);
        $this->surveys = $collection;
    }

    public function view(): View
    {
        return view('excel.export', ['surveys' => $this->surveys]);
    }
}
