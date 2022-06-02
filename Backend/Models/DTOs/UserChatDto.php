<?php

namespace CCS\Models\DTOs;

class UserChatDto implements \JsonSerializable
{

    protected ?string $id = null;
    protected ?ChatRoomDto $chatRoom = null;
    protected ?bool $isAnonymous = null;
    protected ?string $lastSeen = null;

    public function __construct()
    {
    }

    public static function fill($id, $chatRoom, $isAnonymous, $lastSeen)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'chatRoom'} = $chatRoom;
        $instance->{'isAnonymous'} = $isAnonymous;
        $instance->{'lastSeen'} = $lastSeen;
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
