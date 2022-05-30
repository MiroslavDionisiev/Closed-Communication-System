<?php

namespace CCS\Services;

use CCS\Repositories as Repo;

require_once(APP_ROOT . "/Repositories/UserRepository.php");
require_once(APP_ROOT . "/Repositories/MessageRepository.php");
require_once(APP_ROOT . "/Repositories/ChatRoomRepository.php");

foreach (glob(APP_ROOT . '/Models/DTOs/*.php') as $file) {
    require_once($file);
};

class AdminService
{

    public static function getAllDisabledMessages()
    {
        return array_map('CCS\Models\DTOs\MessageDto::fromEntity', Repo\MessageRepository::getAllDisabledMessages());
    }

    public static function getAllUsers()
    {
        return array_map('CCS\Models\DTOs\UserDto::fromEntity', Repo\UserRepository::getAllUsers());
    }

    public static function getAllChatRooms()
    {
        return array_map('CCS\Models\DTOs\ChatRoomDto::fromEntity', Repo\ChatRoomRepository::getAllChatRooms());
    }

    public static function createChatRoom($chatRoomDto)
    {
        Repo\ChatRoomRepository::createChatRoom($chatRoomDto->{'name'}, $chatRoomDto->{'availabilityDate'}, $chatRoomDto->{'isActive'});
    }

    public static function addUserToChatRoom($userChatDto)
    {
        if (Repo\ChatRoomRepository::existsById($userChatDto->{'chatRoomId'})) {
            throw new \InvalidArgumentException("Chatroom with ID {$userChatDto->{'chatRoomId'}} doesn't exist.");
        }
        if (Repo\UserRepository::existsById($userChatDto->{'userId'})) {
            throw new \InvalidArgumentException("User with ID {$userChatDto->{'userId'}} doesn't exist.");
        }
        Repo\UserChatRepository::createUserChat($userChatDto->{'chatRoomId'}, $userChatDto->{'userId'}, $userChatDto->{'isAnonymous'});
    }

    public static function updateChatRoomActive($chatRoomDto)
    {
        if (Repo\ChatRoomRepository::existsById($chatRoomDto->{'id'})) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomDto->{'id'}} doesn't exist.");
        }
        if (!is_bool($chatRoomDto->{'isActive'})) {
            throw new \InvalidArgumentException("isActive should be boolean, you gave ${gettype($chatRoomDto->{'isActive'})}.");
        }
        Repo\ChatRoomRepository::updateChatRoomActive($chatRoomDto->{'id'}, $chatRoomDto->{'isActive'});
    }

    public static function removeUserFromChat($userId, $chatRoomId)
    {
        if (Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomId} doesn't exist.");
        }
        if (Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }
        Repo\UserChatRepository::deleteUserChatByUserIdAndChatRoomId($userId, $chatRoomId);
    }

    public static function deleteMessageById($messageId)
    {
        if (Repo\MessageRepository::existsById($messageId)) {
            throw new \InvalidArgumentException("Message with ID {$messageId} doesn't exist.");
        }
        Repo\MessageRepository::deleteMessageById($messageId);
    }

    public static function deleteChatRoomById($chatRoomId)
    {
        if (Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomId} doesn't exist.");
        }
        Repo\ChatRoomRepository::deleteChatRoomById($chatRoomId);
    }
}
