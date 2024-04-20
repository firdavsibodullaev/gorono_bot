<?php

namespace App\Telegram;

use App\Actions\BotUser\BotUserByFromIdChatIdAction;
use App\Actions\BotUser\BotUserCreateAction;
use App\DTOs\BotUser\BotUserCreateDTO;
use App\Enums\Language;
use App\Exceptions\WrongInstanceException;
use App\Models\BotUser;
use App\Modules\Telegram\DTOs\Response\MyChatMemberDTO;
use App\Modules\Telegram\DTOs\Response\UpdateDTO;
use App\Modules\Telegram\Enums\ChatMemberStatus;

class MyChatMember extends BaseUpdate
{
    protected MyChatMemberDTO $my_chat_member;
    public int $from_id;
    public int $chat_id;
    public ChatMemberStatus $status;

    public function __construct(protected UpdateDTO $update)
    {
        parent::__construct($this->update);

        $this->my_chat_member = $this->update->my_chat_member;
        $this->from_id = $this->update->my_chat_member->from->id;
        $this->chat_id = $this->update->my_chat_member->chat->id;
        $this->status = $this->my_chat_member->new_chat_member->status;
    }

    public function index(): void
    {
        try {
            $user = $this->user();
            if ($user->status->is($this->status)) {
                return;
            }
            $user->update(['status' => $this->status]);
        } catch (WrongInstanceException) {
        }

    }

    /**
     * @return BotUser
     * @throws WrongInstanceException
     */
    public function user(): BotUser
    {
        $user = BotUserByFromIdChatIdAction::fromIds($this->from_id, $this->chat_id)->run();

        if (!$user) {
            $user = BotUserCreateAction::make(
                payload: new BotUserCreateDTO(
                    from_id: $this->from_id,
                    chat_id: $this->chat_id,
                    status: $this->status,
                    language: Language::tryFrom($this->update->my_chat_member->from->language_code) ?? Language::Uz
                )
            )
                ->run();
        }

        return $user;
    }
}
