<?php

namespace CCS\Repositories;

use CCS\Helpers as HELPERS;
use CCS\Database\DatabaseConnection as DB;

require_once(APP_ROOT . "/Repositories/UserRepository.php");
require_once(APP_ROOT . '/Database/DatabaseConnection.php');
require_once(APP_ROOT . '/Models/Mappers/UserMapper.php');

class UserRepository
{

    public static function getAllUsers()
    {
        $con = new DB();
        $query = "SELECT u.*, s.studentFacultyNumber, s.studentYear, s.studentSpeciality, s.studentFaculty FROM users u\n" .
            "LEFT OUTER JOIN teachers t ON t.userId = u.userId\n" .
            "LEFT OUTER JOIN students s ON s.userId = u.userId";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\UserMapper::toEntity', $rows ? $rows : []);
    }

    public static function getAllStudents()
    {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN students s ON s.userId = u.userId";

        $rows = $con->query($query)->fetchAll();

        return array_map('CCS\Models\Mappers\UserMapper::toEntity', $rows ? $rows : []);
    }

    public static function findById(
        $userId
    ) {
        $con = new DB();
        $query = "SELECT u.*, s.studentFacultyNumber, s.studentYear, s.studentSpeciality, s.studentFaculty FROM users u\n" .
            "LEFT OUTER JOIN students s ON s.userId = u.userId\n" .
            "LEFT OUTER JOIN teachers t ON t.userId = u.userId\n" .
            "WHERE u.userId = :userId";
        $params = [
            "userId" => $userId
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row ? $row : null);
    }

    public static function findStudentByFacultyNumber(
        $studentFacultyNumber
    ) {
        $con = new DB();
        $query = "SELECT * FROM users u\n" .
            "INNER JOIN students s ON s.userId = u.userId\n" .
            "WHERE s.studentFacultyNumber = :studentFacultyNumber";
        $params = [
            "studentFacultyNumber" => $studentFacultyNumber
        ];

        $row = $con->query($query, $params)->fetch();

        return call_user_func('CCS\Models\Mappers\UserMapper::toEntity', $row ? $row : null);
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

    public static function existsByEmail(
        $email
    ) {
        $con = new DB();
        $query = "SELECT * FROM users\n" .
            "WHERE userEmail = :userEmail";
        $params = [
            "userEmail" => $email
        ];

        $row = $con->query($query, $params)->fetch();

        return $row;
    }

    public static function createStudent(
        $name,
        $email,
        $password,
        $year,
        $speciality,
        $faculty,
        $facultyNumber,
    ) {
        try {
            $con = (new DB())->getConnection();
            $con->beginTransaction();

            $query = "INSERT INTO users(userName, userEmail, userPassword, userRole)\n" .
                "VALUES (:userName, :userEmail, :userPassword, :userRole)";
            $params = [
                "userName"     => $name,
                "userEmail"    => $email,
                "userPassword" => password_hash($password, PASSWORD_BCRYPT),
                "userRole"     => HELPERS\GlobalConstants::USER_ROLE
            ];

            $stmt = $con->prepare($query);
            $stmt->execute($params);

            $query = "SELECT * FROM users\n" .
                "WHERE userEmail = :userEmail";
            $params = [
                "userEmail" => $email,
            ];

            $stmt = $con->prepare($query);
            $stmt->execute($params);

            $query = "INSERT INTO students(userId, studentFacultyNumber, studentYear, studentSpeciality, studentFaculty)\n" .
                "VALUES (:userId, :studentFacultyNumber, :studentYear, :studentSpeciality, :studentFaculty)";
            $params = [
                "userId"               => $stmt->fetch()->{'userId'},
                "studentFacultyNumber" => $facultyNumber,
                "studentYear"          => $year,
                "studentSpeciality"    => $speciality,
                "studentFaculty"       => $faculty
            ];
            $stmt = $con->prepare($query);
            $stmt->execute($params);

            $con->commit();

        } catch (\PDOException $e) {
            throw $e;
        }
    }
}
