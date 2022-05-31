<?php

namespace CCS\Models\Entities;

class Message
{

    private ?string $id = null;
    private ?User $user = null;
    private ?string $content = null;
    private ?string $timestamp = null;
    private ?bool $isDisabled = null;

    public function __construct()
    {
    }

    public static function fill($id, $user, $content, $timestamp, $isDisabled)
    {
        $instance = new self();
        $instance->id = $id;
        $instance->user = $user;
        $instance->content = $content;
        $instance->timestamp = $timestamp;
        $instance->isDisabled = $isDisabled;
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
                $instance->{$key} = (get_class($key))::fromObject($entity->{$key});
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

}
