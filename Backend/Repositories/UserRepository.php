<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/UserMapper.php');

class UserRepository
{

    public static function getAllTeachers()
    {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN teachers t ON t.userId = u.userId";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\UserMapper::toEntity', $rows);
    }

    public static function getAllStudents()
    {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN students s ON s.userId = u.userId";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\UserMapper::toEntity', $rows);
    }

    public static function findStudentById($userId)
    {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN students s ON s.userId = u.userId\n" .
            "WHERE userId = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
    }

    public static function findStudentByFacultyNumber(
        $studentFacultyNumber
    ) {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN students s ON s.userId = u.userId\n" .
            "WHERE studentFacultyNumber = :studentFacultyNumber";
        $params = [
            "studentFacultyNumber" => $studentFacultyNumber
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
    }

    public static function findTeacherById(
        $userId
    ) {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN teachers t ON t.userId = u.userId";
        "WHERE userId = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row);
    }

    public static function existsById(
        $userId
    ) {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "WHERE userId = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }
}
