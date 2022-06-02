<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/MessageMapper.php');

class MessageRepository
{

    public static function createMessage($userId, $chatRoomId, $content, $isDisabled)
    {
        $con = new DB();
        $query = "INSERT INTO messages(userId, chatRoomId, content, isDisabled)\n" .
            "VALUES (:userId, :chatRoomId, :content, :isDisabled)";

        $params = [
            "iserId" => $userId,
            "chatRoomId" => $chatRoomId,
            "content" => $content,
            "isDisabled" => $isDisabled
        ];

        $con->query($query, $params);
    }

    public static function getAllDisabledMessages()
    {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "WHERE isDisabled IS TRUE";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\MessageMapper::toEntity', $rows);
    }

    public static function getAllChatRoomMessages($chatRoomId) {
        $con = new DB();
        $query = "SELECT messages.*, users.*, user_chats.isAnonymous FROM messages\n".
            "INNER JOIN users ON users.id = messages.userId\n".
            "INNER JOIN user_chats ON user_chats.userId = users.id\n".
            "WHERE messages.chatRoomId = :chatRoomId AND messages.isDisabled IS TRUE";

        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $rows = $con->query($query, $params)->fetchAll();

        $messages = array_map('CCS\Models\Mappers\MessageMapper::toEntity', $rows);

        foreach ($rows  as $index => $row) {
            if (!$row["isAnonymous"]) {
                $messages[$index]->user = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
            }
        }

        return $messages;
    }

    public static function getAllChatRoomMessagesFromTimestamp($chatRoomId, $timestamp) {
        $con = new DB();
        $query = "SELECT messages.*, users.*, user_chats.isAnonymous FROM messages\n".
            "INNER JOIN users ON users.id = messages.userId\n".
            "INNER JOIN user_chats ON user_chats.userId = users.id\n".
            "WHERE messages.chatRoomId = :chatRoomId AND messages.timestamp > :timestamp AND messages.isDisabled IS TRUE";

        $params = [
            "chatRoomId" => $chatRoomId,
            "timestamp" => $timestamp
        ];

        $rows = $con->query($query, $params)->fetchAll();

        $messages = array_map('CCS\Models\Mappers\MessageMapper::toEntity', $rows);

        foreach ($rows  as $index => $row) {
            if (!$row["isAnonymous"]) {
                $messages[$index]->user = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
            }
        }

        return $messages;
    }

    public static function deleteMessageById($msgId)
    {
        $con = new DB();
        $query = "DELETE FROM messages\n" .
            "WHERE id = :msgId";
        $params = [
            "msgId" => $msgId
        ];

        $con->query($query, $params);
    }

    public static function existsById($messageId)
    {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "WHERE id = :messageId";
        $params = [
            "messageId" => $messageId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }
}
