<?php
    namespace CCS\Mappers;

    use CCS\Models\DTOs\ChatRoomDto;
    use CCS\Models\Entities\ChatRoom;

    class UserChatMapper {
        public static function convertToDtoFromEntity($entity) {
            return ChatRoomDto::fill($entity->id, $entity->name, $entity->availabilityDate, $entity->isActive);
        }

        public static function convertToEntityFromArray(array $arr) {
            $message = new ChatRoom();
            
            foreach (get_object_vars($message) as $key => $_) {
                if (isset($arr[$key])) {
                    $message->{$key} = $arr[$key];
                }
            }

            return $message;
        }
    }
?>