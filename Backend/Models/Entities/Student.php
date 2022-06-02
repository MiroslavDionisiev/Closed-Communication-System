<?php

namespace CCS\Models\Entities;

require_once(APP_ROOT . '/Models/Entities/User.php');

class Student extends User
{

    private $studentFacultyNumber = null;
    private $studentYear          = null;
    private $studentSpeciality    = null;
    private $studentFaculty       = null;

    public function __construct()
    {
    }

    public static function fill(
        $userId,
        $userName,
        $userEmail,
        $userPassword,
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
        $instance->{'userPassword'}         = $userPassword;
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
}
