<?php

namespace CCS\Repositories;

use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/StudentMapper.php');
require_once(APP_ROOT . '/Models/Mappers/TeacherMapper.php');

class UserRepository
{

    public static function getAllTeachers()
    {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN teachers t ON t.userId = u.id";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\TeacherMapper::toEntity', $rows);
    }

    public static function getAllStudents()
    {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN students s ON s.userId = u.id";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\StudentMapper::toEntity', $rows);
    }

    public static function findStudentById($userId)
    {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "INNER JOIN students s ON s.userId = u.id" .
            "WHERE id = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\StudentMapper::toEntity', $row);
    }

    public static function findStudentByFacultyNumber($facultyNumber)
    {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "INNER JOIN students ON userId = id\n" .
            "WHERE facultyNumber = :facultyNumber";
        $params = [
            "facultyNumber" => $facultyNumber
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\StudentMapper::toEntity', $row);
    }

    public static function findTeacherById($userId)
    {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "INNER JOIN teachers t ON t.userId = u.id";
        "WHERE id = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\TeacherMapper::toEntity', $row);
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
