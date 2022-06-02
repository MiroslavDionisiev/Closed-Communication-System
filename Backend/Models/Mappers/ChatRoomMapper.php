<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/ChatRoomDto.php');
require_once(APP_ROOT . '/Models/Entities/ChatRoom.php');

class ChatRoomMapper
{
    public static function toEntity($from)
    {
        return Enti\ChatRoom::fill(
            $from->{'id'} ?? null,
            $from->{'name'} ?? null,
            $from->{'availabilityDate'} ?? null,
            $from->{'isActive'} ?? null,
        );
    }

    public static function toDto($from)
    {
        return Dtos\ChatRoomDto::fill(
            $from->{'id'} ?? null,
            $from->{'name'} ?? null,
            $from->{'availabilityDate'} ?? null,
            $from->{'isActive'} ?? null,
        );
    }
}

