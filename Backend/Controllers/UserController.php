<?php

    namespace CCS\Controllers;

    use CCS\Services\UserService;

    require_once(APP_ROOT . "/Services/UserService.php");
    foreach (glob(APP_ROOT . '/Models/DTOs.php') as $file) {
        require_once($file);
    };

    class UserController {
        public static function getAllUserChats() {
            $userId = $_SESSION['user']->{'userId'};
            echo json_encode(UserService::getAllUserChats($userId));
        }

        public static function getAllChatRoomMessages($param) {
            echo json_encode(UserService::getAllChatRoomMessages($param['chatRoomId'], $_GET['lastTimestamp'] ?? null));
        }

        public static function createMessage($param) {
            $body = json_decode(file_get_contents('php://input'), true);
            UserService::createMessage($_SESSION['user']->{'userId'}, $param['chatRoomId'], $body['message']);
        }
    }
?>
