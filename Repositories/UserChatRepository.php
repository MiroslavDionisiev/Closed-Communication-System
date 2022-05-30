<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Entities/UserChat.php');

class UserChatRepository
{

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
