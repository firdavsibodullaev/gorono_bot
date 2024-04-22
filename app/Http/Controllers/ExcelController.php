<?php

namespace App\Http\Controllers;

use App\Exports\SchoolExport;
use App\Models\Survey;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelController extends Controller
{
    public function __invoke(): Response|BinaryFileResponse
    {
        $surveys = Survey::query()
            ->where('is_finished', '=', true)->with(['botUser.district', 'botUser.school'])
            ->get();

        $export = new SchoolExport($surveys);

        $time = now()->format('Y-m-d_H-i-s');

        return $export->download("excel/survey-$time.xlsx");
    }
}
