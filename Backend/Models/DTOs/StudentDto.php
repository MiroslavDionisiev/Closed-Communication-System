<?php

namespace CCS\Models\DTOs;

require_once(APP_ROOT . '/Models/DTOs/UserDto.php');

class StudentDto extends UserDto implements \JsonSerializable
{

    protected $studentFacultyNumber = null;
    protected $studentYear          = null;
    protected $studentSpeciality    = null;
    protected $studentFaculty       = null;

    public function __construct()
    {
    }

    public static function fill(
        $userId,
        $userName,
        $userEmail,
        $userRole,
        $studentFacultyNumber,
        $studentYear,
        $studentSpeciality,
        $studentFaculty
    ) {
        $instance = new self();
        $instance->{'userId'}               = $userId;
        $instance->{'userName'}             = $userName;
        $instance->{'userEmail'}            = $userEmail;
        $instance->{'userRole'}             = $userRole;
        $instance->{'studentFacultyNumber'} = $studentFacultyNumber;
        $instance->{'studentYear'}          = $studentYear;
        $instance->{'studentSpeciality'}    = $studentSpeciality;
        $instance->{'studentFaculty'}       = $studentFaculty;
        return $instance;
    }

    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->{$prop};
        }
    }

    public function __set($prop, $value)
    {
        if (property_exists($this, $prop)) {
            $this->{$prop} = $value;
        }
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($val) {
            return !is_null($val);
        });
    }
}
