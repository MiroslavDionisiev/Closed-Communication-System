<?php

namespace CCS\Models\DTOs;

require_once(APP_ROOT . '/Models/DTOs/UserDto.php');

class StudentDto extends UserDto implements \JsonSerializable
{

    protected $facultyNumber = null;
    protected $year = null;
    protected $speciality = null;
    protected $faculty = null;

    public function __construct()
    {
    }

    public static function fill($id, $name, $email, $role, $facultyNumber, $year, $speciality, $faculty)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'name'} = $name;
        $instance->{'email'} = $email;
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

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($val) {
            return !is_null($val);
        });
    }
}
