<?php

namespace App\Telegram;

use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Telegram\Commands\Start;
use App\Telegram\Commands\Tozalash;

class HandleCommand extends BaseUpdate
{
    /**
     * @var string
     */
    protected string $command;

    public function __construct(protected UpdateDTO $update)
    {
        parent::__construct($this->update);

        $this->command = str($this->update->message->text)->remove('/')->toString();
    }

    public function index(): void
    {
        $commandClass = match ($this->command) {
            'start' => new Start($this->update->message),
            'tozalash' => new Tozalash($this->update->message)
        };

        $commandClass();
    }
}
