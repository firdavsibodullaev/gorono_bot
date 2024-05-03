<?php

namespace App\Enums;

enum BotUserPostMessageStatus: string
{
    case Process = 'process';
    case Success = 'success';
    case Fail = 'fail';

}
