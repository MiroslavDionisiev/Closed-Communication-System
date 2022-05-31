<?php

namespace CCS\Models\Entities;

class UserChat
{

    private ?string $id = null;
    private ?ChatRoom $chatRoom = null;
    private ?bool $isAnonymous = null;

    public function __construct()
    {
    }

    public static function fill($id, $chatRoom, $isAnonymous)
    {
        $instance = new self();
        $instance->{'id'} = $id;
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
