<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/ChatRoomMapper.php');

class ChatRoomRepository
{

    public static function createChatRoom(
        $chatRoomDto
    ) {
        $db = new DB();
        $con = $db->getConnection();
        $con->beginTransaction();

        $query = "INSERT INTO chat_rooms(chatRoomName, chatRoomAvailabilityDate)\n" .
            "VALUES (:chatRoomName, :chatRoomAvailabilityDate)";
        $params = [
            "chatRoomName"             => $chatRoomDto->{'chatRoomName'},
            "chatRoomAvailabilityDate" => $chatRoomDto->{'chatRoomAvailabilityDate'},
        ];

        $stmt = $con->prepare($query);
        $stmt->execute($params);

        $query = "SELECT * FROM chat_rooms WHERE chatRoomName = :chatRoomName";
        $params = [
            "chatRoomName" => $chatRoomDto->{'chatRoomName'},
        ];

        $stmt = $con->prepare($query);
        $stmt->execute($params);

        $con->commit();

        $row = $stmt->fetch();

        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $row ? $row : null);
    }

    public static function createChatRoomWithUserChats(
        $chatRoomDto,
        $userChatDtos
    ) {
        $db = new DB();
        $con = $db->getConnection();
        $con->beginTransaction();

        $query = "INSERT INTO chat_rooms(chatRoomName, chatRoomAvailabilityDate)\n" .
            "VALUES (:chatRoomName, :chatRoomAvailabilityDate)";
        $params = [
            "chatRoomName"             => $chatRoomDto->{'chatRoomName'},
            "chatRoomAvailabilityDate" => $chatRoomDto->{'chatRoomAvailabilityDate'},
        ];

        $stmt = $con->prepare($query);
        $stmt->execute($params);

        $query = "SELECT * FROM chat_rooms WHERE chatRoomName = :chatRoomName";
        $params = [
            "chatRoomName" => $chatRoomDto->{'chatRoomName'},
        ];

        $stmt = $con->prepare($query);
        $stmt->execute($params);
        $chatRoomId = $stmt->fetch()->{'chatRoomId'};

        $query = "INSERT INTO user_chats(userId, chatRoomId, userChatIsAnonymous)\n" .
            "VALUES (:userId, :chatRoomId, :userChatIsAnonymous)";

        foreach ($userChatDtos as $userChatDto) {
            $params = [
                "userId"              => $userChatDto->{'userId'},
                "chatRoomId"          => $chatRoomId,
                "userChatIsAnonymous" => $userChatDto->{'userChatIsAnonymous'}
            ];
            $stmt = $con->prepare($query);
            $stmt->execute($params);
        }

        $con->commit();
    }

    public static function updateChatRoom(
        $chatRoomDto
    ) {
        $con = new DB();
        $query = "UPDATE chat_rooms\n" .
            "SET chatRoomIsActive = :chatRoomIsActive\n" .
            "WHERE chatRoomId = :chatRoomId";
        $params = [
            "chatRoomId"               => $chatRoomDto->{'chatRoomId'},
            "chatRoomAvailabilityDate" => $chatRoomDto->{'chatRoomAvailabilityDate'},
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

        return array_map('CCS\Models\Mappers\ChatRoomMapper::toEntity', $rows ? $rows : []);
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

        return call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $row ? $row : null);
    }

    public static function existsByName(
        $chatRoomName
    ) {
        $con = new DB();
        $query = "SELECT * FROM chat_rooms\n" .
            "WHERE chatRoomName = :chatRoomName";
        $params = [
            "chatRoomName" => $chatRoomName
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }
}
