<table>
    <thead>
    <tr>
        <th>№</th>
        <th>FISh</th>
        <th>Tuman</th>
        <th>Maktab</th>
        <th>Maqsad</th>
    </tr>
    </thead>
    <tbody>
    @foreach($surveys as $survey)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $survey->botUser->name }}</td>
            <td>{{ $survey->botUser->district->name_uz }}</td>
            <td>{{ $survey->botUser->school->name_uz }}</td>
            <td>{{ $survey->result }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
