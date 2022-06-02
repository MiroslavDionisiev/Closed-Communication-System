<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/ChatRoomMapper.php');

class ChatRoomRepository
{

    public static function createChatRoom($name, $availabilityDate = null, $isActive = true)
    {
        $con = new DB();
        $query = "INSERT INTO chat_rooms(name, availabilityDate, isActive)\n" .
            "VALUES (:name, :availabilityDate, :isActive)";
        $params = [
            "name"              => $name,
            "availabilityDate"  => $availabilityDate,
            "isActive"          => $isActive ?? true
        ];

        $con->query($query, $params);

        $query = "SELECT * FROM chat_rooms WHERE name = :name";

        $params = [
            "name" => $name,
        ];

        $result = $con->query($query, $params);

        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $result->fetch());
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

        return array_map('CCS\Models\Mappers\ChatRoomMapper::toEntity', $rows);
    }

    public static function findById($chatRoomId) {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms\n" .
            "WHERE id = :chatRoomId";
        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $row);

    }
}
