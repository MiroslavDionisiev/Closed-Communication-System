<?php

define('APP_ROOT', __DIR__ . '/Backend');
define('URL_ROOT', '/Closed-Communication-System/');

define('JSON_FLAGS', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

require_once(APP_ROOT . '/Configs/DatabaseConfig.php');

$regex_uuid = '[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}';

define('ROUTES', [
    "GET ^" . URL_ROOT . "/index.php/admin/disabled-messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllDisabledMessages'
    ],
    "GET ^" . URL_ROOT . "/index.php/admin/users$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllUsers'
    ],
    "GET ^" . URL_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllChatRooms'
    ],
    "GET ^" . URL_ROOT . "/index.php/user/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['USER', 'ADMIN'],
        'controller' => 'UserController',
        'controllerMethod' => 'getAllUserChats'
    ],
    "GET ^" . URL_ROOT . "/index.php/user/chat-rooms/messages$" => [
        'authenticate' => true,
        'authorize' => ['USER', 'ADMIN'],
        'controller' => 'UserController',
        'controllerMethod' => 'getAllChatRoomMessages'
    ],
    "POST ^" . URL_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'createChatRoom'
    ],
    "POST ^" . URL_ROOT . "/index.php/user/chat-rooms/messages$" => [
        'authenticate' => true,
        'authorize' => ['USER', 'ADMIN'],
        'controller' => 'UserController',
        'controllerMethod' => 'createMessage'
    ],
    "POST ^" . URL_ROOT . "/index.php/admin/chat-rooms/from-csv$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'createUserChatRoomFromCsv'
    ],
    "PUT ^" . URL_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateChatRoomActive'
    ],
    "DELETE ^" . URL_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteChatRoomById'
    ],
    "DELETE ^" . URL_ROOT . "/index.php/admin/user-chats$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'removeUserFromChat'
    ],
    "DELETE ^" . URL_ROOT . "/index.php/admin/messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteMessageById'
    ],
]);
