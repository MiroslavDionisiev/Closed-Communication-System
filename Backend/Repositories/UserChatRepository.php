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
        $query = "SELECT * FROM user_chats\n" .
            "INNER JOIN users ON users.userId = user_chats.userId\n".
            "WHERE user_chats.chatRoomId = :chatRoomId";

        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $rows = $con->query($query, $params)->fetchAll();
        $res = [];

        foreach ($rows as $row) {
            $user               = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
            $userChat           = call_user_func('CCS\Models\Mappers\UserChatMapper::toEntity', $row);
            $userChat->{'user'} = $user;
            $res[]              = $userChat;
        }
        return $res;
    }

    public static function getAllUserChats(
        $userId
    ) {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms\n" .
            "INNER JOIN user_chats ON user_chats.chatRoomId = chat_rooms.chatRoomId\n" .
            "WHERE user_chats.userId = :userId";

        $params = [
            "userId" => $userId
        ];

        $rows = $con->query($query, $params)->fetchAll();

        $userChats = [];

        foreach (($rows ? $rows : []) as $row) {
            $chatRoom               = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $row);
            $userChat               = call_user_func('CCS\Models\Mappers\UserChatMapper::toEntity', $row);
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
        $query = "SELECT * FROM user_chats\n" .
            "WHERE user_chats.userId = :userId AND user_chats.chatRoomId = :chatRoomId";

        $params = [
            "userId"     => $userId,
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserChatMapper::toEntity', $row ? $row : null);
    }

    public static function existsByIds(
        $userId,
        $chatRoomId
    ) {
        $con = new DB();
        $query = "SELECT * FROM user_chats\n" .
            "WHERE user_chats.userId = :userId AND user_chats.chatRoomId = :chatRoomId";
        $params = [
            "userId"     => $userId,
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
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

    public static function updateUserLastSeen(
        $chatRoomId,
        $userId,
        $timestamp
    ) {
        $con = new DB();
        $query = "UPDATE user_chats\n" .
            "SET userChatLastSeen = :lastSeen\n" .
            "WHERE chatRoomId = :chatRoomId AND userId = :userId";
        $params = [
            "chatRoomId" => $chatRoomId,
            "userId"     => $userId,
            "lastSeen"   => $timestamp
        ];

        $con->query($query, $params);
    }

    public static function updateUserAnonymity(
        $chatRoomId,
        $userId,
        $isAnonymouse
    ) {
        $con = new DB();
        $query = "UPDATE user_chats\n" .
            "SET userChatIsAnonymous = :isAnonymouse, userChatHasResponded = true\n" .
            "WHERE chatRoomId = :chatRoomId AND userId = :userId";
        $params = [
            "chatRoomId" => $chatRoomId,
            "userId"     => $userId,
            "isAnonymouse"   => $isAnonymouse
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
