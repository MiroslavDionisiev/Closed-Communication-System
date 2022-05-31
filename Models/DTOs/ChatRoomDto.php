<?php

namespace CCS\Models\DTOs;

class ChatRoomDto implements \JsonSerializable
{

    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $availabilityDate = null;
    protected ?bool $isActive = null;

    public function __construct()
    {
    }

    public static function fill($id, $name, $availabilityDate, $isActive)
    {
        $instance = new self();
        $instance->id = $id;
        $instance->name = $name;
        $instance->availabilityDate = $availabilityDate;
        $instance->isActive = $isActive;
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
        return get_object_vars($this);
    }
}
