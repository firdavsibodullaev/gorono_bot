<?php

namespace App\Exports;

use App\Models\BotUser;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class BotUserExport implements FromView
{
    use Exportable;

    private Collection $bot_users;

    public function __construct(Collection $collection)
    {
        $collection->ensure(BotUser::class);
        $this->bot_users = $collection;
    }

    public function view(): View
    {
        return view('excel.bot-user-export', ['bot_users' => $this->bot_users]);
    }
}
