<?php

namespace CCS\Models\DTOs;

class MessageDto implements \JsonSerializable
{

    protected ?string $id = null;
    protected ?UserDto $user = null;
    protected ?string $content = null;
    protected ?string $timestamp = null;

    public function __construct()
    {
    }

    public static function fill($id, $user, $content, $timestamp)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'user'} = $user;
        $instance->{'content'} = $content;
        $instance->{'timestamp'} = $timestamp;
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
