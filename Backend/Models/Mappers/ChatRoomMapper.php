<?php

    namespace CCS\Models\Mappers;

    use CCS\Models\DTOs as DTOs;
    use CCS\Models\Entities as Enti;

    require_once(APP_ROOT . '/Models/DTOs/ChatRoomDto.php');
    require_once(APP_ROOT . '/Models/Entities/ChatRoom.php');

    class ChatRoomMapper {
        public static function toEntity($from) {
            if (is_array($from)) {
                return Enti\ChatRoom::fill(
                    $from['id'] ?? null,
                    $from['name'] ?? null,
                    $from['availabilityDate'] ?? null,
                    $from['isActive'] ?? null,
                );
            } else if (is_object($from)) {
                return Enti\ChatRoom::fill(
                    $from->{'id'} ?? null,
                    $from->{'name'} ?? null,
                    $from->{'availabilityDate'} ?? null,
                    $from->{'isActive'} ?? null,
                );
            }
    
            return null;
        }

        public static function toDto($from)
        {
            if (is_array($from)) {
                return Dtos\ChatRoomDto::fill(
                    $from['id'] ?? null,
                    $from['name'] ?? null,
                    $from['availabilityDate'] ?? null,
                    $from['isActive'] ?? null,
                );
            } else if (is_object($from)) {
                return Dtos\ChatRoomDto::fill(
                    $from->{'id'} ?? null,
                    $from->{'name'} ?? null,
                    $from->{'availabilityDate'} ?? null,
                    $from->{'isActive'} ?? null,
                );
            }
    
            return null;
        }
    }
?>