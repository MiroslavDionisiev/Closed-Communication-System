<?php

namespace CCS\Models\DTOs;

class MessageDto implements \JsonSerializable
{

    protected ?string $id = null;
    protected ?string $userId = null;
    protected ?string $chatRoomId = null;
    protected ?string $content = null;
    protected ?string $timestamp = null;
    protected ?bool $isDisabled = null;

    public function __construct()
    {
    }

    public static function fill($id, $userId, $chatRoomId, $content, $timestamp, $isDisabled)
    {
        $instance = new self();
        $instance->id = $id;
        $instance->userId = $userId;
        $instance->chatRoomId = $chatRoomId;
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

    public static function fromEntity($entity)
    {
        $instance = new self();
        foreach (get_object_vars($instance) as $key => $_) {
            $instance->{$key} = $entity->{$key};
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
