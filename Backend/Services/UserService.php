<?php

namespace CCS\Services;

use CCS\Repositories as Repo;
use MessageManager;
use CCS\Helpers\GlobalConstants as Globals;

require_once(APP_ROOT . "/Repositories/UserRepository.php");
require_once(APP_ROOT . "/Repositories/MessageRepository.php");
require_once(APP_ROOT . "/Repositories/UserChatRepository.php");

foreach (glob(APP_ROOT . '/Models/DTOs/*.php') as $file) {
    require_once($file);
};

class UserService
{

    private static function updateLastSeen(
        $chatRoomId,
        $userId,
        $lastSeen
    ) {
        Repo\UserChatRepository::updateUserLastSeen($chatRoomId, $userId, $lastSeen);
    }

    public static function getAllUserChats(
        $userId
    ) {
        if (!Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }

        $userChatRooms = Repo\UserChatRepository::getAllUserChats($userId);
        $userChatRooms = array_map('CCS\Models\Mappers\UserChatMapper::toDTO', $userChatRooms);

        $res = [];
        foreach ($userChatRooms as $userChatRoom) {
            $messages = Repo\MessageRepository::getAllChatRoomMessagesFromTimestamp($userChatRoom->{'chatRoom'}->{'chatRoomId'}, $userChatRoom->{'userChatLastSeen'});
            $res[] = [
                'userChatRoom' => $userChatRoom,
                'unreadMessages' => count($messages)
            ];
        }
        return $res;
    }

    public static function getAllChatRoomMessages(
        $chatRoomId,
        $date = null
    ) {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} doesn't exist.");
        }

        $chatRoomMessages = null;
        if ($date == null) {
            $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessages($chatRoomId);
        } else {
            $chatRoomMessages = Repo\MessageRepository::getAllChatRoomMessagesFromTimestamp($chatRoomId, $date);
        }

        UserService::updateLastSeen($chatRoomId, $_SESSION['user']->{'userId'}, $chatRoomMessages[count($chatRoomMessages) - 1]->{'messageTimestamp'} ?? null);
        $chatRoomMessages = array_map('CCS\Models\Mappers\MessageMapper::toDTO', $chatRoomMessages);
        return $chatRoomMessages;
    }

    public static function createMessage(
        $userId,
        $chatRoomId,
        $message
    ) {
        $chatRoom = Repo\ChatRoomRepository::findById($chatRoomId);
        if (date('Y-m-d H:i:s', $chatRoom->{'chatRoomAvailabilityDate'}) < date('Y-m-d H:i:s')) {
            throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} has expired.");
        }

        $user = Repo\UserRepository::existsById($userId);
        if (!$user) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }

        $userChatRooms = Repo\UserChatRepository::existsByIds($userId, $chatRoomId);
        if (!$userChatRooms && $user->{'userRole'} !== Globals::ADMIN_ROLE) {
            throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} of user with ID {$userId} doesn't exist.");
        }

        $isDisabled = false;
        if ($user->{'userRole'} !== Globals::ADMIN_ROLE && $userChatRooms->{'userChatIsAnonymous'}) {
            $isDisabled = MessageManager::checkForLeakedCredentials($message, $user->{'userEmail'}, $user->{'userName'});
        }

        Repo\MessageRepository::createMessage($userId, $chatRoomId, $message, $isDisabled);

        return $isDisabled;
    }
}
