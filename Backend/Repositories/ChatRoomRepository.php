<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/ChatRoomMapper.php');

class ChatRoomRepository
{

    public static function createChatRoom(
        $chatRoomName,
        $chatRoomAvailabilityDate = null,
        $chatRoomIsActive = true
    ) {
        $con = new DB();
        $query = "INSERT INTO chat_rooms(chatRoomName, chatRoomAvailabilityDate, chatRoomIsActive)\n" .
            "VALUES (:chatRoomName, :chatRoomAvailabilityDate, :chatRoomIsActive)";
        $params = [
            "chatRoomName"             => $chatRoomName,
            "chatRoomAvailabilityDate" => $chatRoomAvailabilityDate,
            "chatRoomIsActive"         => $chatRoomIsActive ?? true
        ];

        $con->query($query, $params);

        $query = "SELECT * FROM chat_rooms WHERE chatRoomName = :chatRoomName";

        $params = [
            "chatRoomName" => $chatRoomName,
        ];

        $result = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $result);
    }

    public static function updateChatRoomActive(
        $chatRoomId,
        $chatRoomIsActive
    ) {
        $con = new DB();
        $query = "UPDATE chat_rooms\n" .
            "SET chatRoomIsActive = :chatRoomIsActive\n" .
            "WHERE chatRoomId = :chatRoomId";
        $params = [
            "chatRoomId"       => $chatRoomId,
            "chatRoomIsActive" => $chatRoomIsActive,
        ];

        $con->query($query, $params);
    }

    public static function deleteChatRoomById(
        $chatRoomId
    ) {
        $con = new DB();
        $query = "DELETE FROM chat_rooms\n" .
            "WHERE chatRoomId = :chatRoomId";
        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $con->query($query, $params);
    }

    public static function existsById(
        $chatRoomId
    ) {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms\n" .
            "WHERE chatRoomId = :chatRoomId";
        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }

    public static function getAllChatRooms()
    {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\ChatRoomMapper::toEntity', $rows);
    }

    public static function findById(
        $chatRoomId
    ) {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms\n" .
            "WHERE chatRoomId = :chatRoomId";
        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $row);
    }
}
