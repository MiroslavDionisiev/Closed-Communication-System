<?php

namespace CCS\Services;

use CCS\Repositories as Repo;

require_once(APP_ROOT . "/Repositories/UserRepository.php");
require_once(APP_ROOT . "/Repositories/MessageRepository.php");
require_once(APP_ROOT . "/Repositories/ChatRoomRepository.php");
require_once(APP_ROOT . "/Repositories/UserChatRepository.php");

foreach (glob(APP_ROOT . '/Models/DTOs/*.php') as $file) {
    require_once($file);
};

class AdminService
{

    public static function getAllDisabledMessages()
    {
        return array_map('CCS\Models\Mappers\MessageMapper::toDto', Repo\MessageRepository::getAllDisabledMessages());
    }

    public static function getAllUsers()
    {
        return array_map('CCS\Models\Mappers\UserMapper::toDto', Repo\UserRepository::getAllUsers());
    }

    public static function getUserById($userId) {
        if (!Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }
        return call_user_func('CCS\Models\Mappers\UserMapper::toDto', Repo\UserRepository::findById($userId));
    }

    public static function getAllChatRooms()
    {
        return array_map('CCS\Models\Mappers\ChatRoomMapper::toDto', Repo\ChatRoomRepository::getAllChatRooms());
    }

    public static function createChatRoom($chatRoomDto)
    {
        $chatRoom = Repo\ChatRoomRepository::createChatRoom($chatRoomDto->{'chatRoomName'}, $chatRoomDto->{'chatRoomAvailabilityDate'}, $chatRoomDto->{'chatRoomIsActive'});
        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $chatRoom);
    }

    public static function addUserToChatRoom($userChatDto)
    {
        if (!Repo\ChatRoomRepository::existsById($userChatDto->{'chatRoomId'})) {
            throw new \InvalidArgumentException("Chatroom with ID {$userChatDto->{'chatRoomId'}} doesn't exist.");
        }
        if (!Repo\UserRepository::existsById($userChatDto->{'userId'})) {
            throw new \InvalidArgumentException("User with ID {$userChatDto->{'userId'}} doesn't exist.");
        }
        Repo\UserChatRepository::createUserChat($userChatDto->{'chatRoomId'}, $userChatDto->{'userId'}, $userChatDto->{'userChatIsAnonymous'});
    }

    public static function updateChatRoomActive($chatRoomDto)
    {
        if (!Repo\ChatRoomRepository::existsById($chatRoomDto->{'id'})) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomDto->{'chatRoomId'}} doesn't exist.");
        }
        if (!is_bool($chatRoomDto->{'chatRoomIsActive'})) {
            throw new \InvalidArgumentException("isActive should be boolean, you gave ${gettype($chatRoomDto->{'chatRoomIsActive'})}.");
        }
        Repo\ChatRoomRepository::updateChatRoomActive($chatRoomDto->{'chatRoomId'}, $chatRoomDto->{'chatRoomIsActive'});
    }

    public static function removeUserFromChat($userId, $chatRoomId)
    {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomId} doesn't exist.");
        }
        if (!Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }
        Repo\UserChatRepository::deleteUserChatByUserIdAndChatRoomId($userId, $chatRoomId);
    }

    public static function deleteMessageById($messageId)
    {
        if (!Repo\MessageRepository::existsById($messageId)) {
            throw new \InvalidArgumentException("Message with ID {$messageId} doesn't exist.");
        }
        Repo\MessageRepository::deleteMessageById($messageId);
    }

    public static function deleteChatRoomById($chatRoomId)
    {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomId} doesn't exist.");
        }
        Repo\ChatRoomRepository::deleteChatRoomById($chatRoomId);
    }

    public static function createUserChatRoomFromCsv($csvData)
    {
        foreach ($csvData as $row) {
            $chatRoom = Repo\ChatRoomRepository::createChatRoom($row[0], $row[1]);

            for ($i = 2; $i < count($row); $i += 2) {
                $facultyNumber = $row[$i];
                $isAnonymous = filter_var($row[$i + 1] ?? false, FILTER_VALIDATE_BOOLEAN);

                $student = Repo\UserRepository::findStudentByFacultyNumber($facultyNumber);
                if (!$student) {
                    throw new \InvalidArgumentException("Student with faculty number {$facultyNumber} doesn't exist.");
                }
                Repo\UserChatRepository::createUserChat($chatRoom->{'chatRoomId'}, $student->{'userId'}, $isAnonymous);
            }
        }
    }

    public static function updateMessageIsDisabled($msgDto)
    {
        if (!Repo\MessageRepository::existsById($msgDto->{'messageId'})) {
            throw new \InvalidArgumentException("Message with ID {$msgDto->{'messageId'}} doesn't exist.");
        }
        Repo\MessageRepository::updateMessage($msgDto);
    }
}
