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

    public static function getUserById(
        $userId
    ) {
        if (!Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }
        return call_user_func('CCS\Models\Mappers\UserMapper::toDto', Repo\UserRepository::findById($userId));
    }

    public static function getUsersInChatRoom(
        $chatRoomId
    ) {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chat room with ID {$chatRoomId} doesn't exist.");
        }

        return array_map('CCS\Models\Mappers\UserChatMapper::toDto', Repo\UserChatRepository::getUsersInChatRoom($chatRoomId));
    }

    public static function getAllChatRooms()
    {
        return array_map('CCS\Models\Mappers\ChatRoomMapper::toDto', Repo\ChatRoomRepository::getAllChatRooms());
    }

    public static function createChatRoom(
        $chatRoomDto,
        $userChatDtos
    ) {
        if (Repo\ChatRoomRepository::existsByName($chatRoomDto->{'chatRoomName'})) {
            throw new \InvalidArgumentException("Chatroom with name {$chatRoomDto->{'chatRoomName'}} already exists.");
        }
        if ($chatRoomDto->{'chatRoomName'} === '') {
            throw new \InvalidArgumentException("Chatroom name cannot be empty.");
        }

        if ($userChatDtos) {
            $chatRoom = Repo\ChatRoomRepository::createChatRoomWithUserChats($chatRoomDto, $userChatDtos);
        } else {
            $chatRoom = Repo\ChatRoomRepository::createChatRoom($chatRoomDto);
        }
        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $chatRoom);
    }

    public static function addUserToChatRoom(
        $userChatDto
    ) {
        if (!Repo\ChatRoomRepository::existsById($userChatDto->{'chatRoomId'})) {
            throw new \InvalidArgumentException("Chatroom with ID {$userChatDto->{'chatRoomId'}} doesn't exist.");
        }
        if (!Repo\UserRepository::existsById($userChatDto->{'userId'})) {
            throw new \InvalidArgumentException("User with ID {$userChatDto->{'userId'}} doesn't exist.");
        }
        Repo\UserChatRepository::createUserChat($userChatDto->{'chatRoomId'}, $userChatDto->{'userId'}, $userChatDto->{'userChatIsAnonymous'});
    }

    public static function updateChatRoom(
        $chatRoomDto
    ) {
        if (!Repo\ChatRoomRepository::existsById($chatRoomDto->{'id'})) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomDto->{'chatRoomId'}} doesn't exist.");
        }
        Repo\ChatRoomRepository::updateChatRoom($chatRoomDto);
    }

    public static function removeUserFromChat(
        $userId,
        $chatRoomId
    ) {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomId} doesn't exist.");
        }
        if (!Repo\UserRepository::existsById($userId)) {
            throw new \InvalidArgumentException("User with ID {$userId} doesn't exist.");
        }
        Repo\UserChatRepository::deleteUserChatByUserIdAndChatRoomId($userId, $chatRoomId);
    }

    public static function deleteMessageById(
        $messageId
    ) {
        if (!Repo\MessageRepository::existsById($messageId)) {
            throw new \InvalidArgumentException("Message with ID {$messageId} doesn't exist.");
        }
        Repo\MessageRepository::deleteMessageById($messageId);
    }

    public static function deleteChatRoomById(
        $chatRoomId
    ) {
        if (!Repo\ChatRoomRepository::existsById($chatRoomId)) {
            throw new \InvalidArgumentException("Chatroom with ID {$chatRoomId} doesn't exist.");
        }
        Repo\ChatRoomRepository::deleteChatRoomById($chatRoomId);
    }

    public static function deleteChatRoomBatch(
        $chatRoomIds
    ) {
        foreach ($chatRoomIds as $id) {
            if (!Repo\ChatRoomRepository::existsById($id)) {
                throw new \InvalidArgumentException("Chatroom with ID {$id} doesn't exist.");
            }
        }
        if (!Repo\ChatRoomRepository::deleteBatch($chatRoomIds)) {
            throw new \InvalidArgumentException("Could not delete batch of chat rooms.");
        }
    }

    public static function createChatRoomFromCsv(
        $csvData
    ) {
        foreach ($csvData as $row) {
            $chatRoomDto = (object) [
                'chatRoomName'             => $row[0],
                'chatRoomAvailabilityDate' => $row[1] === 'null' ? null : $row[1]
            ];

            $userChatDtos = [];

            for ($i = 2; $i < count($row); $i += 2) {
                $student = Repo\UserRepository::findStudentByFacultyNumber($row[$i]);
                if (!$student) {
                    throw new \InvalidArgumentException("Student with faculty number {$row[$i]} doesn't exist.");
                }

                if (in_array($student->{'userId'}, array_column($userChatDtos, 'userId'))) {
                    continue;
                }

                $userChatDtos[] = (object) [
                    'userId'              => $student->{'userId'},
                    'userChatIsAnonymous' => filter_var($row[$i + 1] ?? false, FILTER_VALIDATE_BOOLEAN)
                ];
            }

            Repo\ChatRoomRepository::createChatRoomWithUserChats($chatRoomDto, $userChatDtos);
        }
    }

    public static function updateMessageIsDisabled(
        $msgDto
    ) {
        if (!Repo\MessageRepository::existsById($msgDto->{'messageId'})) {
            throw new \InvalidArgumentException("Message with ID {$msgDto->{'messageId'}} doesn't exist.");
        }
        Repo\MessageRepository::updateMessage($msgDto);
    }
}
