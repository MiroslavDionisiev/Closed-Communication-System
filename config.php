<?php

define('APP_ROOT', str_replace('\\', '/', __DIR__) . '/Backend');
define('ENTRY_ROOT', str_replace('\\', '/', __DIR__));

define('JSON_FLAGS', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

use CCS\Helpers\GlobalConstants as Globals;

require_once(APP_ROOT . '/Configs/DatabaseConfig.php');
require_once(APP_ROOT . '/Helpers/GlobalConstants.php');

$pathParam = function ($name) {
    return "(?'${name}'[^/]*)";
};

define('ROUTES', [
    "GET ^" . ENTRY_ROOT . "(/|/index.php)$" => [
        'entry' => true,
        'homePage' => ENTRY_ROOT . '/Frontend/Account/Login/'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/account/logout$" => [
        'authenticate' => true,
        'controller' => 'AccountController',
        'controllerMethod' => 'logout'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/account/is-authenticated$" => [
        'authenticate' => true,
        'controller' => 'AccountController',
        'controllerMethod' => 'isAuthenticated'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/account/register$" => [
        'authenticate' => false,
        'controller' => 'AccountController',
        'controllerMethod' => 'register'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/account/login$" => [
        'authenticate' => false,
        'controller' => 'AccountController',
        'controllerMethod' => 'login'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/disabled-messages$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllDisabledMessages'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/users$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllUsers'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/users/{$pathParam('userId')}$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'getUserById'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms/{$pathParam('chatRoomId')}/users$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'getUsersInChatRoom'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/{$pathParam('chatRoomId')}/users$" => [
        'authenticate' => true,
        'authorize' => [Globals::USER_ROLE],
        'controller' => 'UserController',
        'controllerMethod' => 'getUsersInChatRoom'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'getAllChatRooms'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/user/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => [Globals::USER_ROLE, Globals::ADMIN_ROLE],
        'controller' => 'UserController',
        'controllerMethod' => 'getAllUserChats'
    ],
    "GET ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/{$pathParam('chatRoomId')}/messages$" => [
        'authenticate' => true,
        'authorize' => [Globals::USER_ROLE, Globals::ADMIN_ROLE],
        'controller' => 'UserController',
        'controllerMethod' => 'getAllChatRoomMessages'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'createChatRoom'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/{$pathParam('chatRoomId')}/messages$" => [
        'authenticate' => true,
        'authorize' => [Globals::USER_ROLE, Globals::ADMIN_ROLE],
        'controller' => 'UserController',
        'controllerMethod' => 'createMessage'
    ],
    "POST ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms/from-csv$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'createChatRoomFromCsv'
    ],
    "PUT ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms/{$pathParam('chatRoomId')}$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateChatRoom'
    ],
    "PUT ^" . ENTRY_ROOT . "/index.php/admin/disabled-messages/{$pathParam('messageId')}$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateMessageIsDisabled'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/disabled-messages/{$pathParam('messageId')}$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteMessageById'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms/{$pathParam('chatRoomId')}$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteChatRoomById'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/user-chats$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'removeUserFromChat'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/messages$" => [
        'authenticate' => true,
        'authorize' => [Globals::ADMIN_ROLE],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteMessageById'
    ],
]);
