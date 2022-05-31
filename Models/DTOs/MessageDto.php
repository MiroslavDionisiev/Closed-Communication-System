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
        $instance->id = $id;
        $instance->user = $user;
        $instance->content = $content;
        $instance->timestamp = $timestamp;
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

    public static function fromObject($entity)
    {
        $instance = new self();
        foreach (get_object_vars($instance) as $key => $_) {
            if (is_object($key)) {
                $instance->{$key} = (get_class($key)."Dto")::fromObject($entity->{$key});
            }
            else {
                $instance->{$key} = $entity->{$key};
            }
        }
        return $instance;
    }

    public static function fromArray(array $arr)
    {
        $instance = new self();
        foreach (get_object_vars($instance) as $key => $_) {
            if (isset($arr[$key])) {
                $instance->{$key} = $arr[$key];
            }
        }
        return $instance;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
