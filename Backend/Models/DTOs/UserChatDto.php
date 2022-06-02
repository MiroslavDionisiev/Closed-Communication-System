<?php

namespace CCS\Models\DTOs;

class UserChatDto implements \JsonSerializable
{

    protected $id = null;
    protected $user = null;
    protected $chatRoom = null;
    protected $isAnonymous = null;

    public function __construct()
    {
    }

    public static function fill($id, $user, $chatRoom, $isAnonymous)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'user'} = $user;
        $instance->{'chatRoom'} = $chatRoom;
        $instance->{'isAnonymous'} = (bool) $isAnonymous;
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
