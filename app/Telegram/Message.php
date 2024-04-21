<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\BotUser\BotUserCreateAction;
use App\DTOs\BotUser\BotUserCreateDTO;
use App\Enums\Language;
use App\Enums\MainMessage;
use App\Exceptions\UpdateNotPermittedException;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Enums\ChatType;
use App\Telegram\Action\Action;

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

        if (!$update->message || !$update->message->chat->type->is(ChatType::Private)) {
            throw new UpdateNotPermittedException("Only private chat is allowed");
        }

        $this->message = $update->message;
        $this->from_id = $update->message->from->id;
        $this->chat_id = $update->message->chat->id;
        $this->text = $update->message->text;
        $this->action = Action::make($this->from_id, $this->chat_id);
        $this->setLanguage();

    }

    public function index(): void
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        if (!$user) {
            $user = BotUserCreateAction::make(BotUserCreateDTO::fromMessage($this->message))->run();
        }

        if ($this->message->isCommand() && $this->isCommand()) {
            HandleCommand::make($this->update)->index();
            return;
        }

        if (!$user->is_registered) {
            (new Registration($this->message))->index();
            return;
        }

        $action = Action::make($this->message->from->id, $this->chat_id)->get();
        $main_message = $this->getMainMessage();

        if (!$action?->class && $main_message) {
            $action = Action::make($this->from_id, $this->chat_id)->set($main_message->class());
        }

        if ($action) {
            (new $action->class($this->message, $main_message))->index();
            return;
        }

        SendMainMessage::send($this->message->from->id, $this->chat_id);
    }

    private function isCommand(): bool
    {
        return in_array($this->text, ['/start', '/tozalash']);
    }

    private function getMainMessage(): MainMessage|false
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        return MainMessage::fromText($this->text, $user->language);
    }

    private function setLanguage(): void
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $language = $user?->language ?: (Language::tryFrom($this->message->from->language_code) ?? Language::Uz);

        app()->setLocale($language->value);
    }
}
