@php use App\Enums\AfterSchoolGoal;use App\Models\Survey; @endphp
@php /** @var Survey $survey */ @endphp
<table>
    <thead>
    <tr>
        <th>№</th>
        <th>FISh</th>
        <th>Tuman</th>
        <th>Maktab</th>
        <th>Maqsad so'ngi maqsad</th>
        <th>Oliy o‘quv yurtlariga topshirish uchun tayyorgarlik</th>
        <th>Oliy ta'lim muassasasalari turlari</th>
        <th>Ishlash sohasi</th>
        <th>Kasb-hunar</th>
    </tr>
    </thead>
    <tbody>
    @foreach($surveys as $survey)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $survey->botUser->name }}</td>
            <td>{{ $survey->botUser->district->name_uz }}</td>
            <td>{{ $survey->botUser->school->name_uz }}</td>
            <td>{{ $survey->type->is(AfterSchoolGoal::Other) ? $survey->job_direction : $survey->after_school_goal }}</td>
            <td>{{ $survey->university_preparation_method }}</td>
            <td>{{ $survey->university_type }}</td>
            <td>{{ $survey->type->is(AfterSchoolGoal::WantToWork) ? $survey->job_direction : '' }}</td>
            <td>{{ $survey->type->is(AfterSchoolGoal::WantToStudyProfession) ? $survey->job_direction : '' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
