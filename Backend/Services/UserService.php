<?php
    namespace CCS\Services;

    use CCS\Repositories as Repo;
    use CCS\Models as Models;
use CCS\Repositories\MessageRepository;
use MessageManager;

    require_once(APP_ROOT . "/Repositories/UserRepository.php");
    require_once(APP_ROOT . "/Repositories/MessageRepository.php");
    require_once(APP_ROOT . "/Repositories/UserChatRepository.php");
    
    foreach (glob(APP_ROOT . '/Models/DTOs/*.php') as $file) {
        require_once($file);
    };

    class UserService {

        private static function updateLastSeen($chatRoomId, $userID) {
            $date = date('m/d/Y h:i:s a', time());
            $timestamp = strtotime($date);
            Repo\UserChatRepository::updateUserLastSeen($chatRoomId, $userID, $timestamp);
        }

        public static function getAllUserChats($userId) {
            if (!Repo\UserRepository::existsById($userId)) {
                throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
            }

            $userChatRooms = Repo\UserChatRepository::getAllUserChats($userId);
            $userChatRooms = array_map('CCS\Models\Mappers\UserChatMapper::toDTO', $userChatRooms);

            $res = [];
            foreach ($userChatRooms as $userChatRoom) {
                $messages = MessageRepository::getAllChatRoomMessagesFromTimestamp($userChatRoom->{'chatRoom'}->{'id'}, $userChatRoom->{'lastSeen'});
                $res += [
                    [
                        'userChatRoom' => $userChatRoom,
                        'unreadMessages' => count($messages)
                    ]
                ];
            }
            return $res;
        }

        public static function getAllChatRoomMessages($chatRoomId, $timestamp = null) {
            if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
                throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} doesn't exist.");
            }

            $chatRoomMessages = null;
            if ($timestamp == null) {
                $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessages($chatRoomId);
            }
            else {
                $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessagesFromTimestamp($chatRoomId, $timestamp);
            }

            UserService::updateLastSeen($chatRoomId, $_SESSION['user']->{'id'});

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
            UserService::updateLastSeen($chatRoomId, $userId);

        }
    }
?>