<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/StudentDto.php');
require_once(APP_ROOT . '/Models/DTOs/TeacherDto.php');
require_once(APP_ROOT . '/Models/Entities/Student.php');
require_once(APP_ROOT . '/Models/Entities/Teacher.php');

class UserMapper
{

    public static function toEntity($from)
    {
        if (($from->{'role'} ?? null) === "ADMIN") {
            return Enti\Teacher::fill(
                $from->{'id'} ?? null,
                $from->{'name'} ?? null,
                $from->{'email'} ?? null,
                $from->{'password'} ?? null,
                $from->{'role'} ?? null,
            );
        } else {
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
    }

    public static function toDto($from)
    {
        if (($from->{'role'} ?? null) === "ADMIN") {
            return DTOs\TeacherDto::fill(
                $from->{'id'} ?? null,
                $from->{'name'} ?? null,
                $from->{'email'} ?? null,
                $from->{'role'} ?? null,
            );
        } else {
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
    }
}
