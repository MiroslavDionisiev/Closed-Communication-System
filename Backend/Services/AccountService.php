<?php

namespace CCS\Services;

use CCS\Repositories as Repo;
use CCS\Helpers as Helpers;

require_once(APP_ROOT . "/Helpers/AuthorizationManager.php");
require_once(APP_ROOT . "/Repositories/UserRepository.php");

foreach (glob(APP_ROOT . '/Models/DTOs/*.php') as $file) {
    require_once($file);
};

class AccountService
{
    public static function createStudent(
        $studentDto
    ) {
        if (Repo\UserRepository::existsByEmail($studentDto->{'userEmail'})) {
            throw new \InvalidArgumentException("User with email '{$studentDto->{'userEmail'}}' already exists!");
        }

        Repo\UserRepository::createStudent(
            $studentDto->{'userName'},
            $studentDto->{'userEmail'},
            $studentDto->{'userPassword'},
            $studentDto->{'studentYear'},
            $studentDto->{'studentSpeciality'},
            $studentDto->{'studentFaculty'},
            $studentDto->{'studentFacultyNumber'},
        );
    }

    public static function loginUser(
        $email,
        $password
    ) {
        $user = Repo\UserRepository::existsByEmail($email);
        if (!$user) {
            throw new \InvalidArgumentException("Invalid email address or password");
        }

        if (!password_verify($password, $user->{'userPassword'})) {
            throw new \InvalidArgumentException("Invalid email address or password");
        }

        Helpers\AuthorizationManager::login($user);

        return $user;
    }

    public static function userExistsByEmail($email) {
        $result = Repo\UserRepository::existsByEmail($email);
        return is_bool($result) ? $result : true;
    }

    public static function userExistsByFacultyNumber($studentFacultyNumber) {
        $result = Repo\UserRepository::existsByFacultyNumber($studentFacultyNumber);
        return is_bool($result) ? $result : true;

    }
}
