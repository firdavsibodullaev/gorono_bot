<?php

namespace App\Telegram\Update;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Enums\AfterSchoolGoal;
use App\Enums\Language;
use App\Exceptions\UpdateNotPermittedException;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Enums\ChatType;
use App\Telegram\Action\Action;
use App\Telegram\Update\Message\PrivateMessage;

class Message extends BaseUpdate
{
    protected MessageDTO $message;
    protected int $from_id;
    protected int $chat_id;
    protected ?string $text;
    protected Action $action;

    /**
     * @throws UpdateNotPermittedException
     */
    public function __construct(protected UpdateDTO $update)
    {
        parent::__construct($this->update);

        if (!$update->message) {
            throw new UpdateNotPermittedException("Only messages are allowed");
        }

        $this->message = $update->message;
    }

    /**
     * @throws UpdateNotPermittedException
     */
    public function index(): void
    {
        match ($this->message->chat->type) {
            ChatType::Private => (new PrivateMessage($this->update))->index(),
            default => ''
        };
    }

    private function isCommand(): bool
    {
        $commands = ['/start'];

        if (app()->isLocal()) {
            $commands[] = '/tozalash';
        }

        return in_array($this->text, $commands);
    }

    private function getMainMessage(): AfterSchoolGoal|false
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        return AfterSchoolGoal::fromText($this->text, $user->language);
    }

    private function setLanguage(): void
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $language = $user?->language ?: (Language::tryFrom($this->message->from->language_code) ?? Language::Uz);

        app()->setLocale($language->value);
    }
}
