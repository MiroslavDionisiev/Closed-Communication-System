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

            return array_map('CCS\Models\Mappers\UserChatMapper::toDTO', $userChatRooms);
        }

        public static function getAllChatRoomMessages($chatRoomId) {
            if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
                throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} doesn't exist.");
            }
            
            $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessages($chatRoomId);

            return array_map('CCS\Models\Mappers\MessageMapper::toDto', $chatRoomMessages);
        }

        public static function createMessage($userId, $chatRoomId, $message) {
            $userChatRooms = Repo\UserChatRepository::getUserChatByIds($userId, $chatRoomId);
            if (!$userChatRooms) {
                throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} of user with Id {$userId}doesn't exist.");
            }

            $user = Repo\UserRepository::existsById($userId);
            if (!$user) {
                throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
            }

            $isDisabled = false;
            if ($userChatRooms->isAnonymous) {
                $isDisabled = MessageManager::checkForLeakedCredentials($message, $user['email'], $user['name']);
            }

            Repo\MessageRepository::createMessage($userId, $chatRoomId, $message, $isDisabled);
        }
    }
?>