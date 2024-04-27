<?php

namespace App\Telegram\Update\Message;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\BotUser\BotUserCreateAction;
use App\DTOs\BotUser\BotUserCreateDTO;
use App\Enums\AfterSchoolGoal;
use App\Enums\BotUserType;
use App\Enums\Language;
use App\Exceptions\UpdateNotPermittedException;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Enums\ChatType;
use App\Telegram\Action\Action;
use App\Telegram\Update\BaseUpdate;
use App\Telegram\Update\Message\Private\HandleCommand;
use App\Telegram\Update\Message\Private\Registration;
use App\Telegram\Update\Message\Private\SendMainMessage;

class PrivateMessage extends BaseUpdate
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

        if (!$update->message->chat->type->is(ChatType::Private)) {
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
        $commands = ['/start'];

        if (app()->isLocal()) {
            $commands[] = '/tozalash';
        }

        $admins = config('services.telegram.admin');

        if (in_array($this->message->from->id, $admins)) {
            $commands[] = '/export';
        }

        return in_array($this->text, $commands);
    }

    private function getMainMessage(): AfterSchoolGoal|false
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        if ($user->type->is(BotUserType::Student)) {
            return false;
        }

        return AfterSchoolGoal::fromText($this->text, $user->language);
    }

    private function setLanguage(): void
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        $language = $user?->language ?: (Language::tryFrom($this->message->from->language_code) ?? Language::Uz);

        app()->setLocale($language->value);
    }
}
