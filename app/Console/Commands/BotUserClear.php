<?php

namespace App\Console\Commands;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Telegram\Action\Action;
use Illuminate\Console\Command;

class BotUserClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bot-user-clear
                            {from_id : Bot user from_id}
                            {chat_id? : Bot user chat_id (optional, if not passed, chat id equals to from id)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Удаление данных пользователя';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $from_id = (int)$this->argument('from_id');

        $chat_id = (int)($this->argument('chat_id') ?? $from_id);

        $user = BotUserByFromIdChatIdAction::fromIds($from_id, $chat_id)->run();

        if (!$user) {
            $this->components->warn("Пользователь не найден");
            return 1;
        }

        $name = $user->name;

        $user->surveys()->forceDelete();
        $user->forceDelete();
        Action::make($from_id, $chat_id)->clear();

        $this->components->info("$name, Очищено");

        return 0;
    }
}
