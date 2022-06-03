<?php

namespace CCS\Models\Entities;

require_once(APP_ROOT . '/Models/Entities/User.php');

class Teacher extends User
{

    public function __construct()
    {
    }

    public static function fill(
        $userId,
        $userName,
        $userEmail,
        $userPassword,
        $userRole,
        $userIdentity
    ) {
        $instance = new self();
        $instance->{'userId'}       = $userId;
        $instance->{'userName'}     = $userName;
        $instance->{'userEmail'}    = $userEmail;
        $instance->{'userPassword'} = $userPassword;
        $instance->{'userRole'}     = $userRole;
        $instance->{'userIdentity'} = $userRole;
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
