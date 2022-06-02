<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;
use CCS\Models\Entities\User;

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
        $query = "SELECT * FROM messages\n".
            "WHERE isDisabled IS TRUE";

        $rows = $con->query($query)->fetchAll();

        foreach ($rows as &$row) {
            $row->{'user'} = call_user_func('CCS\Repositories\UserRepository::findStudentById', $row->{'userId'});
            $row->{'chatRoom'} = call_user_func('CCS\Repositories\ChatRoomRepository::findById', $row->{'chatRoomId'});
        }

        return array_map('CCS\Models\Mappers\MessageMapper::toEntity', $rows);
    }

    public static function getAllChatRoomMessages($chatRoomId) {
        $con = new DB();
        $query = "SELECT messages.*, users.*, user_chats.isAnonymous FROM messages\n".
            "INNER JOIN users ON users.id = messages.userId\n".
            "INNER JOIN user_chats ON user_chats.userId = users.id\n".
            "WHERE messages.chatRoomId = :chatRoomId AND messages.isDisabled IS FALSE";

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

    public static function findById($messageId) {
        $con = new DB();
        $query = "SELECT * FROM messages\n" .
            "WHERE id = :messageId";
        $params = [
            "messageId" => $messageId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\MessageMapper::toEntity', $row);
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

    public static function updateMessage($messageDto) {
        $msg = MessageRepository::findById($messageDto->{'id'});
        $msg->{'content'} = $messageDto->{'content'} ?? $msg->{'content'};
        $msg->{'isDisabled'} = $messageDto->{'content'} ?? $msg->{'content'};

        $con = new DB();
        $query = "UPDATE messages\n" .
            "SET isDisabled = :isDisabled, content = :content\n" .
            "WHERE id = :id";
        $params = [
            "id" => $msg->{'id'},
            "isDisabled" => $msg->{'isDisabled'},
            "content" => $msg->{'content'},
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }
}
