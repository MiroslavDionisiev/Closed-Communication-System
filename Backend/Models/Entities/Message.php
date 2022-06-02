<?php

namespace CCS\Models\Entities;

class Message
{

    private $id = null;
    private $user = null;
    private $chatRoom = null;
    private $content = null;
    private $timestamp = null;
    private $isDisabled = null;

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
}
