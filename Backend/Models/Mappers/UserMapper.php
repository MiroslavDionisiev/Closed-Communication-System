<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;
use CCS\Helpers\GlobalConstants as Globals;

require_once(APP_ROOT . '/Models/DTOs/StudentDto.php');
require_once(APP_ROOT . '/Models/DTOs/TeacherDto.php');
require_once(APP_ROOT . '/Models/Entities/Student.php');
require_once(APP_ROOT . '/Models/Entities/Teacher.php');
require_once(APP_ROOT . '/Helpers/GlobalConstants.php');

class UserMapper
{

    public static function toEntity(?object $from)
    {
        if(is_null($from)) return null;

        if (($from->{'userRole'} ?? null) === Globals::$ADMIN_ROLE) {
            return Enti\Teacher::fill(
                $from->{'userId'}       ?? null,
                $from->{'userName'}     ?? null,
                $from->{'userEmail'}    ?? null,
                $from->{'userPassword'} ?? null,
                $from->{'userRole'}     ?? null,
            );
        } else {
            return Enti\Student::fill(
                $from->{'userId'}               ?? null,
                $from->{'userName'}             ?? null,
                $from->{'userEmail'}            ?? null,
                $from->{'userPassword'}         ?? null,
                $from->{'userRole'}             ?? null,
                $from->{'studentFacultyNumber'} ?? null,
                $from->{'studentYear'}          ?? null,
                $from->{'studentSpeciality'}    ?? null,
                $from->{'studentFaculty'}       ?? null,
            );
        }
    }

    public static function toDto(?object $from)
    {
        if(is_null($from)) return null;

        if (($from->{'userRole'} ?? null) === Globals::$ADMIN_ROLE) {
            return DTOs\TeacherDto::fill(
                $from->{'userId'}       ?? null,
                $from->{'userName'}     ?? null,
                $from->{'userEmail'}    ?? null,
                $from->{'userRole'}     ?? null,
            );
        } else {
            return DTOs\StudentDto::fill(
                $from->{'userId'}               ?? null,
                $from->{'userName'}             ?? null,
                $from->{'userEmail'}            ?? null,
                $from->{'userRole'}             ?? null,
                $from->{'studentFacultyNumber'} ?? null,
                $from->{'studentYear'}          ?? null,
                $from->{'studentSpeciality'}    ?? null,
                $from->{'studentFaculty'}       ?? null,
            );
        }
    }
}
