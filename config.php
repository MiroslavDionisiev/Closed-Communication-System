<?php

define('APP_ROOT', __DIR__.'/Backend');
define('URL_ROOT', '/');

define('JSON_FLAGS', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

require_once(APP_ROOT . '/Configs/DatabaseConfig.php');

$regex_uuid = '[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}';

define('ROUTES', [
    "GET /index.php/admin/disabled-messages" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllDisabledMessages'
    ],
    "GET /index.php/admin/users" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllUsers'
    ],
    "GET /index.php/admin/chat-rooms" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllChatRooms'
    ],
    "POST /index.php/admin/chat-rooms" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'createChatRoom'
    ],
    "PUT /index.php/admin/chat-rooms" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateChatRoomActive'
    ],
    "DELETE /index.php/admin/chat-rooms" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteChatRoomById'
    ],
    "DELETE /index.php/admin/user-chats" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'removeUserFromChat'
    ],
    "DELETE /index.php/admin/messages" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteMessageById'
    ],
]);
