<?php

namespace CCS\Models\DTOs;

class ChatRoomDto implements \JsonSerializable
{

    protected $id = null;
    protected $name = null;
    protected $availabilityDate = null;
    protected $isActive = null;

    public function __construct()
    {
    }

    public static function fill($id, $name, $availabilityDate, $isActive)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'name'} = $name;
        $instance->{'availabilityDate'} = $availabilityDate;
        $instance->{'isActive'} = is_null($isActive) ? $isActive : (bool) $isActive;
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
