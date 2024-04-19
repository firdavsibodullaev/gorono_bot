<?php

namespace App\Modules\Telegram\Enums;

enum ChatMemberStatus: string
{
    case Creator = 'creator';
    case Administrator = 'administrator';
    case Member = 'member';
    case Restricted = 'restricted';
    case Left = 'left';
    case Kicked = 'kicked';


    public function is(self $member): bool
    {
        return $this === $member;
    }
}
