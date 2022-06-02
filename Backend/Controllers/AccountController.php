<?php

namespace CCS\Controllers;

use CCS\Services\AccountService;
use CCS\Models\DTOs as DTOs;
use CCS\Helpers as Helpers;

require_once(APP_ROOT . "/Helpers/AuthorizationManager.php");
require_once(APP_ROOT . "/Services/AccountService.php");
foreach (glob(APP_ROOT . '/Models/DTOs.php') as $file) {
    require_once($file);
};

class AccountController {
    public static function register() {
        $userDto = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', json_decode(file_get_contents('php://input')));
        AccountService::createStudent($userDto);
        echo json_encode(new DTOs\ResponseDtoSuccess(201, "Successfully registered."));
    }

    public static function login() {
        $userDto = call_user_func('CCS\Models\Mappers\UserMapper::toEntity', json_decode(file_get_contents('php://input')));
        AccountService::loginUser($userDto->{'userEmail'}, $userDto->{'userPassword'});
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Successfully logged."));
    }

    public static function logout() {
        Helpers\AuthorizationManager::logout();
        echo json_encode(new DTOs\ResponseDtoSuccess(200, "Successfully logout."));
    }

    public static function isAuthenticated() {
        $session = Helpers\AuthorizationManager::authenticate();
        if(!is_null($session)){
            echo json_encode(call_user_func('CCS\Models\Mappers\UserMapper::toDto', $session));
            exit;
        }

        echo json_encode(new DTOs\ResponseDtoError(401, "Unauthorized"));
    }
}