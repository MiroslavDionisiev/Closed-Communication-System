<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/ChatRoomDto.php');
require_once(APP_ROOT . '/Models/Entities/ChatRoom.php');

class ChatRoomMapper
{
    public static function toEntity(?object $from)
    {
        if(is_null($from)) return null;

        return Enti\ChatRoom::fill(
            $from->{'chatRoomId'}               ?? null,
            $from->{'chatRoomName'}             ?? null,
            $from->{'chatRoomAvailabilityDate'} ?? null,
            $from->{'chatRoomIsActive'}         ?? null,
        );
    }

    public static function toDto(?object $from)
    {
        if(is_null($from)) return null;

        return Dtos\ChatRoomDto::fill(
            $from->{'chatRoomId'}               ?? null,
            $from->{'chatRoomName'}             ?? null,
            $from->{'chatRoomAvailabilityDate'} ?? null,
            $from->{'chatRoomIsActive'}         ?? null,
        );
    }
}

