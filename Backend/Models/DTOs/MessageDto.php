<?php

namespace CCS\Models\DTOs;

class MessageDto implements \JsonSerializable
{

    protected $id = null;
    protected $user = null;
    protected $chatRoom = null;
    protected $content = null;
    protected $timestamp = null;
    protected $isDisabled = null;

    public function __construct()
    {
    }

    public static function fill($id, $user, $chatRoom, $content, $timestamp, $isDisabled)
    {
        $instance = new self();
        $instance->{'id'} = $id;
        $instance->{'user'} = $user;
        $instance->{'chatRoom'} = $chatRoom;
        $instance->{'content'} = $content;
        $instance->{'timestamp'} = $timestamp;
        $instance->{'isDisabled'} = is_null($isDisabled) ? $isDisabled : (bool) $isDisabled;
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
