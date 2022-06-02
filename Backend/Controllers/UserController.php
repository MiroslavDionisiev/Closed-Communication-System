<?php

    namespace CCS\Controllers;

    use CCS\Services\UserService;
    use CCS\Models\DTOs as DTOs;

    require_once(APP_ROOT . "/Services/UserService.php");
    foreach (glob(APP_ROOT . '/Models/DTOs.php') as $file) {
        require_once($file);
    };

    class UserController {
        public static function getAllUserChats() {
            $userId = $_SESSION['user']->{'id'};
            echo json_encode(UserService::getAllUserChats($userId));
        }

        public static function getAllChatRoomMessages() {
            $chatRoomId = $_GET['chatRoomId'] ?? null;
            echo json_encode(UserService::getAllChatRoomMessages($chatRoomId));
        }

        public static function createMessage() {
            $body = json_decode(file_get_contents('php://input'), true);
            UserService::createMessage($body['userId'], $body['chatRoomId'], $body['message']);
        }

        public static function getUserFromSession() {
            $res = [
                "id" => $_SESSION['user']->{'id'},
                "name" => $_SESSION['user']->{'name'}
            ];

            echo json_encode($res);
        }
    }
?>