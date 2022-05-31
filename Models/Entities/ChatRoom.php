<?php

namespace CCS\Models\Entities;

class ChatRoom
{

    private ?string $id = null;
    private ?string $name = null;
    private ?string $availabilityDate = null;
    private ?bool $isActive = null;

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
}
