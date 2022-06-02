<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/MessageDto.php');
require_once(APP_ROOT . '/Models/Entities/Message.php');
require_once(APP_ROOT . '/Models/Mappers/UserMapper.php');

class MessageMapper
{
    public static function toEntity(?object $from)
    {
        if(is_null($from)) return null;

        $user     = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $from->{'user'} ?? null);
        $userRoom = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $from->{'chatRoom'} ?? null);

        return Enti\Message::fill(
            $from->{'messageId'}         ?? null,
            $user,
            $userRoom,
            $from->{'messageContent'}    ?? null,
            $from->{'messageTimestamp'}  ?? null,
            $from->{'messageIsDisabled'} ?? null
        );
    }

    public static function toDto(?object $from)
    {
        if(is_null($from)) return null;

        $user     = call_user_func('CCS\Models\Mappers\UserMapper::toDto', $from->{'user'} ?? null);
        $userRoom = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $from->{'chatRoom'} ?? null);

        return DTOs\MessageDto::fill(
            $from->{'messageId'}         ?? null,
            $user,
            $userRoom,
            $from->{'messageContent'}    ?? null,
            $from->{'messageTimestamp'}  ?? null,
            $from->{'messageIsDisabled'} ?? null
        );
    }
}
