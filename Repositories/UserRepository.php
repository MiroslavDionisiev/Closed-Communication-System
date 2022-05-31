<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;
use CCS\Models\Entities\User;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Entities/User.php');

class UserRepository
{

    public static function getAllUsers()
    {
        $con = new DB();
        $query = "SELECT * FROM users";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Entities\User::fromArray', $rows);
    }

    public static function findById($userId)
    {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "WHERE id = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return User::fromArray($row);
    }

    public static function existsById($userId)
    {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "WHERE id = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }
}
