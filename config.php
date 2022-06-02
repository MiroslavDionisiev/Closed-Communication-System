<?php

define('APP_ROOT', str_replace('\\', '/', __DIR__) . '/Backend');
define('ENTRY_ROOT', str_replace('\\', '/', __DIR__));
define('URL_ROOT', $_SERVER['DOCUMENT_ROOT']);

assert(URL_ROOT === ENTRY_ROOT, "Make sure the DocumentRoot matches this configuration's directory");

define('JSON_FLAGS', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

require_once(APP_ROOT . '/Configs/DatabaseConfig.php');

$regex_uuid = '[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}';

define('ROUTES', [
    "GET ^" . ENTRY_ROOT . "/index.php/admin/disabled-messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllDisabledMessages'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/users$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllUsers'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllChatRooms'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/user/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['USER', 'ADMIN'],
        'controller' => 'UserController',
        'controllerMethod' => 'getAllUserChats'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/messages$" => [
        'authenticate' => true,
        'authorize' => ['USER', 'ADMIN'],
        'controller' => 'UserController',
        'controllerMethod' => 'getAllChatRoomMessages'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'createChatRoom'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/messages$" => [
        'authenticate' => true,
        'authorize' => ['USER', 'ADMIN'],
        'controller' => 'UserController',
        'controllerMethod' => 'createMessage'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms/from-csv$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'createUserChatRoomFromCsv'
    ],
    "PUT ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateChatRoomActive'
    ],
    "PUT ^" . URL_ROOT . "/index.php/admin/disabled-messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateMessageIsDisabled'
    ],
    "DELETE ^" . URL_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteChatRoomById'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/user-chats$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'removeUserFromChat'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteMessageById'
    ],
]);
