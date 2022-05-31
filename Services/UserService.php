<?php
    namespace CCS\Services;

    use CCS\Repositories as Repo;
    use CCS\Models as Models;
use MessageManager;

    require_once(APP_ROOT . "/Repositories/UserRepository.php");
    require_once(APP_ROOT . "/Repositories/MessageRepository.php");
    require_once(APP_ROOT . "/Repositories/UserChatRepository.php");
    
    foreach (glob(APP_ROOT . '/Models/DTOs/*.php') as $file) {
        require_once($file);
    };

    class UserService {

        public static function getAllUserChats($userId) {
            if (!Repo\UserRepository::existsById($userId)) {
                throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
            }

            $userChatRooms = Repo\UserChatRepository::getAllUserChats($userId);

            return array_map('CCS\Models\DTOs\UserChatDto::fromObject', $userChatRooms);
        }

        public static function getAllChatRoomMessages($chatRoomId) {
            if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
                throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} doesn't exist.");
            }
            
            $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessages($chatRoomId);

            return array_map('CCS\Models\DTOs\MessageDto::fromObject', $chatRoomMessages);
        }

        public static function createMessage($userId, $chatRoomId, $content,) {
            $userChatRooms = Repo\UserChatRepository::getUserChatRoomByIds($userId, $chatRoomId);
            if ($userChatRooms == false) {
                throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} of user with Id {$userId}doesn't exist.");
            }

            if ($userChatRooms->isAnonymous) {
                MessageManager::checkForLeakedCredentials($content);
            }

            return array_map('CCS\Models\DTOs\MessageDto::fromObject', $chatRoomMessages);
        }
    }
?>