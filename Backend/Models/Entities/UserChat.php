<?php

namespace CCS\Models\Entities;

class UserChat
{

    private $id = null;
    private $user = null;
    private $chatRoom = null;
    private $isAnonymous = null;

    public function __construct()
    {
    }

    public static function fill($id, $user, $chatRoom, $isAnonymous)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'user'} = $user;
        $instance->{'chatRoom'} = $chatRoom;
        $instance->{'isAnonymous'} = $isAnonymous;
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
