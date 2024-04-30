<?php

namespace App\Telegram\Update\Message\Private;

use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Telegram\Commands\Export;
use App\Telegram\Commands\Post;
use App\Telegram\Commands\Start;
use App\Telegram\Commands\Tozalash;
use App\Telegram\Update\BaseUpdate;

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
            'tozalash' => new Tozalash($this->update->message),
            'export' => new Export($this->update->message),
            'post' => new Post($this->update->message)
        };

        $commandClass();
    }
}
