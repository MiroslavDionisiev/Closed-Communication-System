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

class UserService
{

    private static function updateLastSeen($chatRoomId, $userID) {
        $date = date('Y/m/d h:i:s a', time());
        Repo\UserChatRepository::updateUserLastSeen($chatRoomId, $userID, $date);
    }

    public static function getAllUserChats($userId) {
        if (!Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }

        $userChatRooms = Repo\UserChatRepository::getAllUserChats($userId);
        $userChatRooms = array_map('CCS\Models\Mappers\UserChatMapper::toDTO', $userChatRooms);

        $res = [];
        foreach ($userChatRooms as $userChatRoom) {
            $messages = Repo\MessageRepository::getAllChatRoomMessagesFromTimestamp($userChatRoom->{'chatRoom'}->{'userChatId'}, $userChatRoom->{'userChatLastSeen'});
            $res += [
                [
                    'userChatRoom' => $userChatRoom,
                    'unreadMessages' => count($messages)
                ]
            ];
        }
        return $res;
    }

    public static function getAllChatRoomMessages($chatRoomId, $date = null) {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} doesn't exist.");
        }

        $chatRoomMessages = null;
        if ($date == null) {
            $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessages($chatRoomId);
        }
        else {
            $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessagesFromTimestamp($chatRoomId, $date);
        }

        UserService::updateLastSeen($chatRoomId, $_SESSION['user']->{'userId'});
        $chatRoomMessages = array_map('CCS\Models\Mappers\MessageMapper::toDTO', $chatRoomMessages);
        return $chatRoomMessages;
    }

    public static function createMessage(
        $userId,
        $chatRoomId,
        $message
    ) {
        $user = Repo\UserRepository::existsById($userId);
        if (!$user) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }

        $userChatRooms = Repo\UserChatRepository::getUserChatByIds($userId, $chatRoomId);
        if (!$userChatRooms) {
            throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} of user with ID {$userId} doesn't exist.");
        }

        $isDisabled = false;
        if ($userChatRooms->isAnonymous) {
            $isDisabled = MessageManager::checkForLeakedCredentials($message, $user->{'userEmail'}, $user->{'userName'});
        }

        Repo\MessageRepository::createMessage($userId, $chatRoomId, $message, $isDisabled);
    }
}

