<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/MessageDto.php');
require_once(APP_ROOT . '/Models/Entities/Message.php');
require_once(APP_ROOT . '/Models/Mappers/UserMapper.php');

class MessageMapper
{
    public static function toEntity($from)
    {
        return Enti\Message::fill(
            $from->{'id'} ?? null,
            call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $from->{'user'} ?? null),
            call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $from->{'chatRoom'} ?? null),
            $from->{'content'} ?? null,
            $from->{'timestamp'} ?? null,
            $from->{'isDisabled'} ?? null
        );
    }

    public static function toDto($from)
    {
        return DTOs\MessageDto::fill(
            $from->{'id'} ?? null,
            call_user_func('CCS\Models\Mappers\UserMapper::toDto', $from->{'user'} ?? null),
            call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $from->{'chatRoom'} ?? null),
            $from->{'content'} ?? null,
            $from->{'timestamp'} ?? null,
            $from->{'isDisabled'} ?? null
        );
    }
}
