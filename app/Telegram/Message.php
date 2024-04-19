<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\BotUser\BotUserCreateAction;
use App\DTOs\BotUser\BotUserCreateDTO;
use App\Enums\Method;
use App\Exceptions\UpdateNotPermittedException;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Enums\ChatMemberStatus;
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
        $this->text = $update->message->text ?: $update->message->caption;
        $this->action = Action::make($this->from_id, $this->chat_id);

    }

    public function index()
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        if (!$user) {
            $user = BotUserCreateAction::make(
                new BotUserCreateDTO($this->from_id, $this->chat_id, ChatMemberStatus::Member)
            )->run();
        }

        if (!$user->is_registered) {
            (new Registration($this->message))();
            return;
        }

        if ($this->message->isCommand() && $this->isCommand()) {
            HandleCommand::make($this->update)->index();
            return;
        }

        if ($action = Action::make($this->message->from->id, $this->chat_id)->get()) {
            (new $action->class($this->message))();
        }

        (new SendMainMessage($this->message->from->id, $this->chat_id))();
    }

    private function isCommand(): bool
    {
        return $this->text == '/start';
    }
}
