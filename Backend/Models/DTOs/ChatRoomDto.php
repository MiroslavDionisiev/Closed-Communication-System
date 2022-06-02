<?php

namespace CCS\Models\DTOs;

class ChatRoomDto implements \JsonSerializable
{

    protected $chatRoomId               = null;
    protected $chatRoomName             = null;
    protected $chatRoomAvailabilityDate = null;
    protected $chatRoomIsActive         = null;

    public function __construct()
    {
    }

    public static function fill(
        $chatRoomId,
        $chatRoomName,
        $chatRoomAvailabilityDate,
        $chatRoomIsActive
    ) {
        $instance = new self();
        $instance->{'chatRoomId'}               = $chatRoomId;
        $instance->{'chatRoomName'}             = $chatRoomName;
        $instance->{'chatRoomAvailabilityDate'} = $chatRoomAvailabilityDate;
        $instance->{'chatRoomIsActive'}         = is_null($chatRoomIsActive) ? $chatRoomIsActive : (bool) $chatRoomIsActive;
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
