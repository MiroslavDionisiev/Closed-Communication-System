<?php

namespace CCS\Controllers;

use CCS\Services\AdminService;
use CCS\Models\DTOs as DTOs;

require_once(APP_ROOT . "/Services/AdminService.php");
foreach (glob(APP_ROOT . '/Models/DTOs.php') as $file) {
    require_once($file);
};

class AdminController {
    
    public static function getAllDisabledMessages() {
        echo json_encode(AdminService::getAllDisabledMessages(), JSON_FLAGS);
    }

    public static function getAllUsers() {
        echo json_encode(AdminService::getAllUsers(), JSON_FLAGS);
    }

    public static function getUserById($path) {
        echo json_encode(AdminService::getUserById($path['userId']), JSON_FLAGS);
    }

    public static function getUsersInChatRoom($path) {
        echo json_encode(AdminService::getUsersInChatRoom($path['chatRoomId']), JSON_FLAGS);
    }

    public static function getAllChatRooms() {
        echo json_encode(AdminService::getAllChatRooms(), JSON_FLAGS);
    }

    public static function createChatRoom() {
        $json         = json_decode(file_get_contents('php://input'));
        $chatRoomDto  = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', $json);
        $userChatDtos = $json->{'userChats'} ?? [];
        AdminService::createChatRoom($chatRoomDto, $userChatDtos);
        echo json_encode(new DTOs\ResponseDtoSuccess(201, "Chatroom created successfully."));
    }

    public static function addUserToChatRoom() {
        $userChatDto = call_user_func('CCS\Models\Mappers\UserChatMapper::toDto', json_decode(file_get_contents('php://input')));
        AdminService::addUserToChatRoom($userChatDto);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "User added to chatroom successfully."));
    }

    public static function updateChatRoom($param) {
        $chatRoomDto                 = call_user_func('CCS\Models\Mappers\ChatRoomMapper::toDto', json_decode(file_get_contents('php://input')));
        $chatRoomDto->{'chatRoomId'} = $param['chatRoomId'] ?? null;
        AdminService::updateChatRoom($chatRoomDto);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Chatroom updated successfully."));
    }

    public static function removeUserFromChat() {
        $userId     = $_GET['userId'] ?? null;
        $chatRoomId = $_GET['chatRoomId'] ?? null;
        AdminService::removeUserFromChat($userId, $chatRoomId);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "User removed successfully."));
    }

    public static function deleteMessageById($param) {
        AdminService::deleteMessageById($param['messageId'] ?? null);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Message deleted successfully."));
    }

    public static function deleteChatRoomById($param) {
        AdminService::deleteChatRoomById($param['chatRoomId']);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Chatroom deleted successfully."));
    }

    public static function deleteChatRoomBatch() {
        $chatRoomIds = explode(',', file_get_contents('php://input'));
        AdminService::deleteChatRoomBatch($chatRoomIds);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Chatrooms deleted successfully."));
    }

    public static function createChatRoomFromCsv() {
        $csvFile = $_FILES['file']['tmp_name'];
        $csvData = array_map('str_getcsv', file($csvFile));
        AdminService::createChatRoomFromCsv($csvData);
        echo json_encode(new DTOs\ResponseDtoSuccess(201, "Chatrooms created successfully."));
    }

    public static function updateMessageIsDisabled($param) {
        $msgDto                = call_user_func('CCS\Models\Mappers\MessageMapper::toDto', json_decode(file_get_contents('php://input')));
        $msgDto->{'messageId'} = $param['messageId'] ?? null;
        AdminService::updateMessageIsDisabled($msgDto);
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Message updated successfully."));
    }
}
