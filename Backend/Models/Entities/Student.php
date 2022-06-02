<?php

namespace CCS\Models\Entities;

require_once(APP_ROOT . '/Models/Entities/User.php');

class Student extends User
{

    private $facultyNumber = null;
    private $year = null;
    private $speciality = null;
    private $faculty = null;

    public function __construct()
    {
    }

    public static function fill($id, $name, $email, $password, $role, $facultyNumber, $year, $speciality, $faculty)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'name'} = $name;
        $instance->{'email'} = $email;
        $instance->{'password'} = $password;
        $instance->{'role'} = $role;
        $instance->{'facultyNumber'} = $facultyNumber;
        $instance->{'year'} = $year;
        $instance->{'speciality'} = $speciality;
        $instance->{'faculty'} = $faculty;
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
