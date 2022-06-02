<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/MessageMapper.php');
require_once(APP_ROOT . '/Models/Mappers/UserMapper.php');
require_once(APP_ROOT . '/Models/Mappers/ChatRoomMapper.php');

class MessageRepository
{

    public static function createMessage(
        $userId,
        $chatRoomId,
        $messageContent,
        $messageIsDisabled
    ) {
        $con = new DB();
        $query = "INSERT INTO messages(userId, chatRoomId, messageContent, messageIsDisabled)\n" .
            "VALUES (:userId, :chatRoomId, :messageContent, :messageIsDisabled)";

        $params = [
            "userId"            => $userId,
            "chatRoomId"        => $chatRoomId,
            "messageContent"    => $messageContent,
            "messageIsDisabled" => $messageIsDisabled
        ];

        $con->query($query, $params);
    }

    public static function getAllDisabledMessages()
    {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "INNER JOIN users ON users.userId = messages.userId\n" .
            "INNER JOIN students ON students.userId = users.userId\n" .
            "INNER JOIN chat_rooms ON chat_rooms.chatRoomId = messages.chatRoomId\n" .
            "WHERE messageIsDisabled IS TRUE";

        $rows = $con->query($query)->fetchAll();

        $messages = [];

        foreach ($rows as $row) {
            $message               = call_user_func('CCS\Models\Mappers\MessageMapper::toEntity', $row);
            $message->{'user'}     = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
            $message->{'chatRoom'} = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toEntity', $row);
            $messages[]            = $message;
        }

        return $messages;
    }

    public static function getAllChatRoomMessages(
        $chatRoomId
    ) {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "INNER JOIN users ON users.userId = messages.userId\n" .
            "INNER JOIN user_chats ON user_chats.userId = users.userId\n" .
            "WHERE messages.chatRoomId = :chatRoomId AND messages.messageIsDisabled IS FALSE";

        $params = [
            "chatRoomId" => $chatRoomId
        ];

        $rows = $con->query($query, $params)->fetchAll();

        $messages = array_map('CCS\Models\Mappers\MessageMapper::toEntity', $rows);

        foreach ($rows  as $index => $row) {
            if (!$row["userChatIsAnonymous"]) {
                $messages[$index]->{'user'} = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
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

    public static function deleteMessageById(
        $messageId
    ) {
        $con = new DB();
        $query = "DELETE FROM messages\n" .
            "WHERE messageId = :messageId";
        $params = [
            "messageId" => $messageId
        ];

        $con->query($query, $params);
    }

    public static function findById(
        $messageId
    ) {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "WHERE messageId = :messageId";
        $params = [
            "messageId" => $messageId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\MessageMapper::toEntity', $row);
    }

    public static function existsById(
        $messageId
    ) {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "WHERE messageId = :messageId";
        $params = [
            "messageId" => $messageId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }

    public static function updateMessage(
        $messageDto
    ) {
        $msg                        = MessageRepository::findById($messageDto->{'messageId'});
        $msg->{'messageContent'}    = $messageDto->{'messageContent'} ?? $msg->{'messageContent'};
        $msg->{'messageIsDisabled'} = $messageDto->{'messageContent'} ?? $msg->{'messageContent'};

        $con = new DB();
        $query = "UPDATE messages\n" .
            "SET messageIsDisabled = :messageIsDisabled, messageContent = :messageContent\n" .
            "WHERE messageId = :messageId";
        $params = [
            "messageId"         => $msg->{'messageId'},
            "messageIsDisabled" => $msg->{'messageIsDisabled'},
            "messageContent"    => $msg->{'messageContent'},
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }
}
