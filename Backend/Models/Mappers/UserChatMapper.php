<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/UserChatDto.php');
require_once(APP_ROOT . '/Models/Entities/UserChat.php');

class UserChatMapper
{

    public static function toEntity(?object $from)
    {
        if(is_null($from)) return null;

        $user     = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $from->{'user'} ?? null);
        $userRoom = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $from->{'chatRoom'} ?? null);

        return Enti\UserChat::fill(
            $from->{'userChatId'}          ?? null,
            $user,
            $userRoom,
            $from->{'userChatIsAnonymous'} ?? null,
        );
    }

    public static function toDto(?object $from)
    {
        if(is_null($from)) return null;

        $user     = call_user_func('CCS\Models\Mappers\UserMapper::toDto', $from->{'user'} ?? null);
        $userRoom = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $from->{'chatRoom'} ?? null);

        return DTOs\UserChatDto::fill(
            $from->{'userChatId'}          ?? null,
            $user,
            $userRoom,
            $from->{'userChatIsAnonymous'} ?? null,
        );
    }
}
