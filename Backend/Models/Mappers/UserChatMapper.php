<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/UserChatDto.php');
require_once(APP_ROOT . '/Models/Entities/UserChat.php');

class UserChatMapper
{

    public static function toEntity($from)
    {
        return Enti\UserChat::fill(
            $from->{'id'} ?? null,
            call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $from->{'user'} ?? null),
            call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $from->{'chatRoom'} ?? null),
            $from->{'isAnonymous'} ?? null,
        );
    }

    public static function toDto($from)
    {
        return DTOs\UserChatDto::fill(
            $from->{'id'} ?? null,
            call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $from->{'user'} ?? null),
            call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $from->{'chatRoom'} ?? null),
            $from->{'isAnonymous'} ?? null,
        );
    }
}
