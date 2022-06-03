<?php

define('APP_ROOT', str_replace('\\', '/', __DIR__) . '/Backend');
define('ENTRY_ROOT', str_replace('\\', '/', __DIR__));
define('URL_ROOT', $_SERVER['DOCUMENT_ROOT']);

assert(URL_ROOT === ENTRY_ROOT, "Make sure the DocumentRoot matches this configuration's directory");

define('JSON_FLAGS', JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

require_once(APP_ROOT . '/Configs/DatabaseConfig.php');

$pathParam = function ($name) {
    return "(?'${name}'[^/]*)";
};

define('ROUTES', [
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
    "GET ^" . ENTRY_ROOT . "/index.php/admin/users/{$pathParam('userId')}$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'getUserById'
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
    "GET ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/{$pathParam('chatRoomId')}/messages$" => [
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
    "POST ^" . ENTRY_ROOT . "/index.php/user/chat-rooms/{$pathParam('chatRoomId')}/messages$" => [
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
    "PUT ^" . ENTRY_ROOT . "/index.php/admin/disabled-messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'updateMessageIsDisabled'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/disabled-messages$" => [
        'authenticate' => true,
        'authorize' => ['ADMIN'],
        'controller' => 'AdminController',
        'controllerMethod' => 'deleteMessageById'
    ],
    "DELETE ^" . ENTRY_ROOT . "/index.php/admin/chat-rooms$" => [
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
