<?php

namespace App\Telegram;

use App\Enums\Method;
use App\Modules\Telegram\DTOs\Response\MessageDTO;
use App\Telegram\Action\Action;

abstract class BaseAction
{
    public Method $method;
    protected int $from_id;
    protected int $chat_id;
    protected ?string $text;
    protected Action $action;

    /**
     * @throws \Exception
     */
    public function __construct(protected MessageDTO $message)
    {
        $this->checkIsMethodSet();

        $this->from_id = $this->message->from->id;
        $this->chat_id = $this->message->chat->id;
        $this->text = $this->message->text;

        $this->action = Action::make($this->from_id, $this->chat_id);

        $action = $this->action->get();

        if ($action?->class !== static::class || is_null($action->method)) {
            $this->action->set(static::class, $this->method);
        }
    }

    public function index(): bool
    {
        $action = $this->action->get();

        if (!$action || !method_exists($this, $action->method)) {
            SendMainMessage::send($this->from_id, $this->chat_id);
            return false;
        }

        $this->{$action->method}();
        return true;
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function checkIsMethodSet(): void
    {
        if (!isset($this->method)) {
            throw new \Exception("Method not set");
        }
    }
}
