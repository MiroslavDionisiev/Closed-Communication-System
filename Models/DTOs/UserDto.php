<?php

namespace CCS\Models\DTOs;

class UserDto implements \JsonSerializable
{

    protected ?string $id = null;
    protected ?string $name = null;
    protected ?int $year = null;
    protected ?string $speciality = null;
    protected ?string $faculty = null;
    protected ?string $role = null;

    public function __construct()
    {
    }

    public static function fill($id, $name, $year, $speciality, $faculty, $role)
    {
        $instance = new self();
        $instance->id = $id;
        $instance->name = $name;
        $instance->year = $year;
        $instance->speciality = $speciality;
        $instance->faculty = $faculty;
        $instance->role = $role;
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
