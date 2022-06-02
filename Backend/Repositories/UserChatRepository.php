<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/ChatRoomMapper.php');
require_once(APP_ROOT . '/Models/Mappers/UserChatMapper.php');

class UserChatRepository
{

    public static function getUsersInChatRoom(
        $chatRoomId
    ) {
        $con = new DB();
        $query = "SELECT * FROM user_chats" .
            "WHERE user_chats.chatRoomId = :chatRoomId";

        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $rows = $con->query($query, $params)->fetchAll();
        return array_map('CCS\Models\Mappers\UserChatMapper::toEntity', $rows);
    }

    public static function getAllUserChats(
        $userId
    ) {
        $con = new DB();
        $query = "SELECT * FROM chat_room\n" .
            "INNER JOIN user_chats ON user_chats.chatRoomId = chat_room.chatRoomId\n" .
            "WHERE user_chat.userId = :userId";

        $params = [
            "userId" => $userId
        ];

        $rows = $con->query($query, $params)->fetchAll();

        $userChats = [];

        foreach ($rows as $row) {
            $chatRoom               = call_user_func('CSS\Models\Mappers\ChatRoomMapper::toEntity', $row);
            $userChat               = call_user_func('CSS\Models\Mappers\UserChatMapper::toEntity', $row);
            $userChat->{'chatRoom'} = $chatRoom;
            $userChats[]            = $userChat;
        }

        return $userChats;
    }

    public static function getUserChatByIds(
        $userId,
        $chatRoomId
    ) {
        $con = new DB();
        $query = "SELECT * FROM user_chat\n" .
            "WHERE user_chat.userId = :userId AND user_chat.chatRoomId = :chatRoomId";

        $params = [
            "userId"     => $userId,
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserChatMapper::toEntity', $row);
    }

    public static function createUserChat(
        $chatRoomId,
        $userId,
        $userChatIsAnonymous
    ) {
        $con = new DB();
        $query = "INSERT INTO user_chats(chatRoomId, userId, userChatIsAnonymous)\n" .
            "VALUES (:chatRoomId, :userId, :userChatIsAnonymous)";
        $params = [
            "chatRoomId"          => $chatRoomId,
            "userId"              => $userId,
            "userChatIsAnonymous" => $userChatIsAnonymous
        ];

        $con->query($query, $params);
    }

    public static function deleteUserChatByUserIdAndChatRoomId(
        $userId,
        $chatRoomId
    ) {
        $con = new DB();
        $query = "DELETE FROM user_chats\n" .
            "WHERE userId = :userId AND chatRoomId = :chatRoomId";
        $params = [
            "userId"     => $userId,
            "chatRoomId" => $chatRoomId
        ];

        $con->query($query, $params);
    }
}
