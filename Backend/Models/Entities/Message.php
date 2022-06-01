<?php

namespace CCS\Models\Entities;

class Message
{

    private ?string $id = null;
    private ?User $user = null;
    private ?string $content = null;
    private ?string $timestamp = null;

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
}
