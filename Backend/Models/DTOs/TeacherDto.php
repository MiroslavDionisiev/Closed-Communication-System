<?php

namespace CCS\Models\DTOs;

require_once(APP_ROOT . '/Models/DTOs/UserDto.php');

class TeacherDto extends UserDto implements \JsonSerializable
{

    public function __construct()
    {
    }

    public static function fill(
        $userId,
        $userName,
        $userEmail,
        $userRole,
        $userIdentity
    ) {
        $instance = new self();
        $instance->{'userId'}       = $userId;
        $instance->{'userName'}     = $userName;
        $instance->{'userEmail'}    = $userEmail;
        $instance->{'userRole'}     = $userRole;
        $instance->{'userIdentity'} = $userIdentity;
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
