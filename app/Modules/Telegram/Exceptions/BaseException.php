<?php

namespace App\Modules\Telegram\Exceptions;

use App\Modules\Telegram\Exceptions\BadRequest\ChatNotFoundException;
use App\Modules\Telegram\Exceptions\BadRequest\GroupChatMigratedException;
use App\Modules\Telegram\Exceptions\BadRequest\InvalidFileIdException;
use App\Modules\Telegram\Exceptions\BadRequest\MessageCantBeEditedException;
use App\Modules\Telegram\Exceptions\BadRequest\MessageIsEmptyException;
use App\Modules\Telegram\Exceptions\BadRequest\MessageIsNotModifiedException;
use App\Modules\Telegram\Exceptions\BadRequest\UserNotFoundException;
use App\Modules\Telegram\Exceptions\BadRequest\WrongParameterActionException;
use App\Modules\Telegram\Exceptions\Conflict\TerminatedByOtherLongPollException;
use App\Modules\Telegram\Exceptions\Conflict\WebhookIsActiveException;
use App\Modules\Telegram\Exceptions\Forbidden\BotCantSendMessagesToBotsException;
use App\Modules\Telegram\Exceptions\Forbidden\BotWasBlockedByTheUserException;
use App\Modules\Telegram\Exceptions\Forbidden\BotWasKickedFromTheGroupChatException;
use App\Modules\Telegram\Exceptions\Forbidden\UserIsDeactivatedException;
use Exception;

class BaseException extends Exception
{
    public static function throw(array $result): BaseException
    {
        if ($result['error_code'] == 400) {
            return match ($result['description']) {
                "Bad Request: group chat was migrated to a supergroup chat" => static::groupChatMigrated($result),
                "Bad Request: chat not found" => static::chatNotFound($result),
                "Bad Request: invalid file id" => static::invalidFileId($result),
                "Bad Request: message is not modified" => static::messageIsNotModified($result),
                "Bad Request: message text is empty" => static::messageTextIsEmpty($result),
                "[Error]: Bad Request: user not found" => static::userNotFound($result),
                "Bad Request: wrong parameter action in request" => static::wrongParameterAction($result),
                "Bad Request: message can't be edited"=> static::messageCantBeEdited($result),
                default => new BadRequestException($result['description'], $result['error_code'])
            };
        }

        if ($result['error_code'] == 403) {
            return match ($result['description']) {
                "Forbidden: bot was blocked by the user" => static::botWasBlockedByTheUser($result),
                "Forbidden: bot can't send messages to bots" => static::botCantSendMessagesToBots($result),
                "Forbidden: bot was kicked from the group chat" => static::botWasKickedFromTheGroupChat($result),
                "Forbidden: user is deactivated" => static::userIsDeactivated($result),
                default => new ForbiddenException($result['description'], $result['error_code'])
            };
        }

        if ($result['error_code'] == 409) {
            return match ($result['description']) {
                "Conflict: terminated by other long poll or webhook" => static::terminatedByOtherLongPoll($result),
                "Conflict: can't use getUpdates method while webhook is active; use deleteWebhook to delete the webhook first" => static::webhookIsActive($result),
                default => new ConflictException($result['description'], $result['error_code'])
            };
        }

        return match ($result['error_code']) {
            401 => static::unauthorized($result),
            429 => static::tooManyTimes($result),
            default => new BadRequestException($result['description'], $result['error_code'])
        };
    }

    private static function groupChatMigrated(array $result): GroupChatMigratedException
    {
        return (new GroupChatMigratedException($result['description'], $result['error_code']))
            ->setMigrateToChatId($result['parameters']['migrate_to_chat_id']);
    }

    private static function chatNotFound(array $result): ChatNotFoundException
    {
        return new ChatNotFoundException($result['description'], $result['error_code']);
    }

    private static function invalidFileId(array $result): InvalidFileIdException
    {
        return new InvalidFileIdException($result['parameters']['file_id']);
    }

    private static function messageIsNotModified(array $result): MessageIsNotModifiedException
    {
        return new MessageIsNotModifiedException($result['message'], $result['error_code']);
    }

    private static function messageTextIsEmpty(array $result): MessageIsEmptyException
    {
        return new MessageIsEmptyException($result['description'], $result['error_code']);
    }

    private static function userNotFound(array $result): UserNotFoundException
    {
        return new UserNotFoundException($result['description'], $result['error_code']);
    }

    private static function wrongParameterAction(array $result): WrongParameterActionException
    {
        return new WrongParameterActionException($result['description'], $result['error_code']);
    }

    private static function unauthorized(array $result): UnauthorizedException
    {
        return (new UnauthorizedException($result['description'], $result['error_code']));
    }

    private static function terminatedByOtherLongPoll(array $result): TerminatedByOtherLongPollException
    {
        return new TerminatedByOtherLongPollException($result['description'], $result['error_code']);
    }

    private static function tooManyTimes(array $result): TooManyTimesException
    {
        return (new TooManyTimesException($result['description'], $result['error_code']))
            ->setRetryAfter($result['parameters']['retry_after']);
    }

    private static function botWasBlockedByTheUser(array $result): BotWasBlockedByTheUserException
    {
        return new BotWasBlockedByTheUserException($result['description'], $result['error_code']);
    }

    private static function botCantSendMessagesToBots(array $result): BotCantSendMessagesToBotsException
    {
        return new BotCantSendMessagesToBotsException($result['description'], $result['error_code']);
    }

    private static function botWasKickedFromTheGroupChat(array $result): BotWasKickedFromTheGroupChatException
    {
        return new BotWasKickedFromTheGroupChatException($result['description'], $result['error_code']);
    }

    private static function userIsDeactivated(array $result): UserIsDeactivatedException
    {
        return new UserIsDeactivatedException($result['description'], $result['error_code']);
    }

    private static function webhookIsActive(array $result): WebhookIsActiveException
    {
        return new WebhookIsActiveException($result['description'], $result['error_code']);
    }

    private static function messageCantBeEdited(array $result): MessageCantBeEditedException
    {
        return new MessageCantBeEditedException($result['description'], $result['error_code']);
    }
}
