<?php

namespace CCS\Models\Mappers;

use CCS\Models\DTOs as DTOs;
use CCS\Models\Entities as Enti;

require_once(APP_ROOT . '/Models/DTOs/TeacherDto.php');
require_once(APP_ROOT . '/Models/Entities/Teacher.php');

class TeacherMapper
{

    public static function toEntity($from)
    {
        if (is_array($from)) {
            return Enti\Teacher::fill(
                $from['id'] ?? null,
                $from['name'] ?? null,
                $from['email'] ?? null,
                $from['password'] ?? null,
                $from['role'] ?? null,
            );
        } else if (is_object($from)) {
            return Enti\Teacher::fill(
                $from->{'id'} ?? null,
                $from->{'name'} ?? null,
                $from->{'email'} ?? null,
                $from->{'password'} ?? null,
                $from->{'role'} ?? null,
            );
        }

        return null;
    }

    public static function toDto($from)
    {
        if (is_array($from)) {
            return DTOs\TeacherDto::fill(
                $from['id'] ?? null,
                $from['name'] ?? null,
                $from['email'] ?? null,
                $from['role'] ?? null,
            );
        } else if (is_object($from)) {
            return DTOs\TeacherDto::fill(
                $from->{'id'} ?? null,
                $from->{'name'} ?? null,
                $from->{'email'} ?? null,
                $from->{'role'} ?? null,
            );
        }

        return null;
    }
}
