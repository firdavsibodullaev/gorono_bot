@php use App\Models\BotUser; @endphp
@php use App\Modules\Telegram\Enums\ChatMemberStatus; @endphp
@php
    /** @var BotUser $bot_user */
@endphp
<table>
    <thead>
    <tr>
        <th>№</th>
        <th>FISh</th>
        <th>Tug'ilgan sana</th>
        <th></th>
        <th>Telefon</th>
        <th>Tuman</th>
        <th>Maktab</th>
        <th>OTM</th>
        <th>Holati</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bot_users as $bot_user)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $bot_user->name }}</td>
            <td>{{ $bot_user->birthdate->format('d-m-Y') }}</td>
            <td>{{ $bot_user->type->text() }}</td>
            <td>{{ $bot_user->phone_formatted }}</td>
            <td>{{ $bot_user->district?->name_uz }}</td>
            <td>{{ $bot_user->school?->name_uz }}</td>
            <td>{{ $bot_user->university?->name_uz }}</td>
            <td>{{ $bot_user->status->is(ChatMemberStatus::Kicked) ? 'Nofaol' : 'Faol' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
