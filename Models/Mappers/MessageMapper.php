<?php

    namespace CCS\Mappers;

    use CCS\Models\DTOs\MessageDto;
    use CCS\Models\Entities\Message;
    use CCS\Models\Entities\User;

    class MessageMapper {
        public static function convertToDtoFromEntity($entity) {
            $message = new MessageDto();
            $$message->id = $entity->id;
            // for user mapper
            //$$message->user = $user;
            $$message->content = $entity->content;
            $$message->timestamp = $entity->timestamp;

            return $message;
        }

        public static function convertToEntityFromArray(array $arr) {
            $message = new Message();
            $user = new User();
            // call from en
            
            foreach (get_object_vars($message) as $key => $_) {
                if (isset($arr[$key])) {
                    $message->{$key} = $arr[$key];
                }
            }

            return $message;
        }
    }
?>