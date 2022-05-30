<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Entities/ChatRoom.php');

class ChatRoomRepository
{

    public static function createChatRoom($name, $availabilityDate, $isActive)
    {
        $con = new DB();
        $query = "INSERT INTO chat_rooms(name, availabilityDate, isActive)\n" .
            "VALUES (:name, :availabilityDate, :isActive)";
        $params = [
            "name"              => $name,
            "availabilityDate"  => $availabilityDate,
            "isActive"          => $isActive
        ];

        $con->query($query, $params);
    }

    public static function updateChatRoomActive($chatRoomId, $isActive)
    {
        $con = new DB();
        $query = "UPDATE chat_rooms\n" .
            "SET isActive = :isActive\n" .
            "WHERE id = :chatRoomId";
        $params = [
            "chatRoomId"    => $chatRoomId,
            "isActive"      => $isActive,
        ];

        $con->query($query, $params);
    }

    public static function deleteChatRoomById($chatRoomId)
    {
        $con = new DB();
        $query = "DELETE FROM chat_rooms\n" .
            "WHERE id = :chatRoomId";
        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $con->query($query, $params);
    }

    public static function existsById($chatRoomId) {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms\n" .
            "WHERE id = :chatRoomId";
        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }

    public static function getAllChatRooms() {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Entities\ChatRoom::fromArray', $rows);
    }

    public static function getAllChatRoomsOfUser($userId) {
        $con = new DB();
        $query = "SELECT chat_room.*, user_chats.isAnonymous FROM chat_room\n".
            "INNER JOIN user_chats ON user_chats.chatRoomId = chat_room.id\n".
            "WHERE user_chat.userId = :userId";

        $params = [
            "userId" => $userId
        ];

        $rows = $con->query($query, $params)->fetchAll();
        $chatRooms = array_map('CSS\Models\Entities\ChatRoom::fromArray', $rows);
        $userAnonumousity = $rows["isAnonymous"];

        $res = [];
        foreach($chatRooms as $index => $chatRoom) {
            $res += [
                [
                    "chatRoom" => $chatRoom,
                    "isAnonymous" => $userAnonumousity[$index]
                ]
            ];
        }

        return $res;
    }
}
