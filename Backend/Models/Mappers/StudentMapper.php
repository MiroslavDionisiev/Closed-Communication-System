<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/StudentDto.php');
require_once(APP_ROOT . '/Models/Entities/Student.php');

class StudentMapper
{

    public static function toEntity($from)
    {
        if (is_array($from)) {
            return Enti\Student::fill(
                $from['id'] ?? null,
                $from['name'] ?? null,
                $from['email'] ?? null,
                $from['password'] ?? null,
                $from['role'] ?? null,
                $from['facultyNumber'] ?? null,
                $from['year'] ?? null,
                $from['speciality'] ?? null,
                $from['faculty'] ?? null,
            );
        } else if (is_object($from)) {
            return Enti\Student::fill(
                $from->{'id'} ?? null,
                $from->{'name'} ?? null,
                $from->{'email'} ?? null,
                $from->{'password'} ?? null,
                $from->{'role'} ?? null,
                $from->{'facultyNumber'} ?? null,
                $from->{'year'} ?? null,
                $from->{'speciality'} ?? null,
                $from->{'faculty'} ?? null,
            );
        }

        return null;
    }

    public static function toDto($from)
    {
        if (is_array($from)) {
            return DTOs\StudentDto::fill(
                $from['id'] ?? null,
                $from['name'] ?? null,
                $from['email'] ?? null,
                $from['role'] ?? null,
                $from['facultyNumber'] ?? null,
                $from['year'] ?? null,
                $from['speciality'] ?? null,
                $from['faculty'] ?? null,
            );
        } else if (is_object($from)) {
            return DTOs\StudentDto::fill(
                $from->{'id'} ?? null,
                $from->{'name'} ?? null,
                $from->{'email'} ?? null,
                $from->{'role'} ?? null,
                $from->{'facultyNumber'} ?? null,
                $from->{'year'} ?? null,
                $from->{'speciality'} ?? null,
                $from->{'faculty'} ?? null,
            );
        }

        return null;
    }
}
