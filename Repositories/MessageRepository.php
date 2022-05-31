<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;
use CCS\Models\Entities as Entities;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Entities/Message.php');

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

        return array_map('CCS\Models\Entities\Message::fromArray', $rows);
    }

    public static function getAllChatRoomMessages($chatRoomId) {
        $con = new DB();
        $query = "SELECT messages.*, user.*, user_chats.isAnonymous FROM messages\n".
            "INNER JOIN user ON user.id = messages.userId\n".
            "INNER JOIN user_chats ON user_chats.userId = user.id\n".
            "WHERE messages.chatRoomId = :chatRoomId AND messages.isDisabled IS TRUE";

        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $rows = $con->query($query, $params)->fetchAll();

        $messages = array_map('CCS\Models\Entities\Message::fromArray', $rows);

        foreach ($rows  as $index => $row) {
            if (!$row["isAnonymous"]) {
                $messages[$index]->user = Entities\User::fromArray($row);
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
