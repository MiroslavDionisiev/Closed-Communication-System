<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/ChatRoomMapper.php');
require_once(APP_ROOT . '/Models/Mappers/UserChatMapper.php');

class UserChatRepository
{

    public static function getUsersInChatRoom($chatRoomId) {
        $con = new DB();
        $query = "SELECT * FROM user_chats WHERE user_chats.chatRoomId = :chatRoomId";

        $params = [
            "chatRoomId"    => $chatRoomId
        ];

        $rows = $con->query($query, $params)->fetchAll();
        return array_map('CCS\Models\Mappers\UserChatMapper::toEntity', $rows);
    }

    public static function getAllUserChats($userId) {
        $con = new DB();
        $query = "SELECT * FROM chat_room\n".
            "INNER JOIN user_chats ON user_chats.chatRoomId = chat_room.id\n".
            "WHERE user_chat.userId = :userId";

        $params = [
            "userId" => $userId
        ];

        $rows = $con->query($query, $params)->fetchAll();
        $chatRooms = array_map('CSS\Models\Mappers\ChatRoomMapper::toEntity', $rows);
        $userChats = array_map('CSS\Models\Mappers\UserChatMapper::toEntity', $rows);

        foreach ($chatRooms as $index => $chatRoom) {
            $userChats[$index]->chatRoom = $chatRoom;
        }

        return $userChats;
    }

    public static function createUserChat($chatRoomId, $userId, $isAnonymous)
    {
        $con = new DB();
        $query = "INSERT INTO user_chats(chatId, userId, isAnonymous)\n" .
            "VALUES (:chatRoomId, :userId, :isAnonymous)";
        $params = [
            "chatRoomId"    => $chatRoomId,
            "userId"        => $userId,
            "isAnonymous"   => $isAnonymous
        ];

        $con->query($query, $params);
    }

    public static function deleteUserChatByUserIdAndChatRoomId($userId, $chatRoomId)
    {
        $con = new DB();
        $query = "DELETE FROM user_chats\n" .
            "WHERE userId = :userId AND chatRoomId = :chatRoomId";
        $params = [
            "userId"        => $userId,
            "chatRoomId"    => $chatRoomId
        ];

        $con->query($query, $params);
    }
}
