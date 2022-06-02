<?php

namespace CCS\Models\DTOs;

class UserChatDto implements \JsonSerializable
{

    protected $userChatId          = null;
    protected $user                = null;
    protected $chatRoom            = null;
    protected $userChatIsAnonymous = null;

    public function __construct()
    {
    }

    public static function fill(
        $userChatId,
        $user,
        $chatRoom,
        $userChatIsAnonymous
    ) {
        $instance = new self();
        $instance->{'userChatId'}          = $userChatId;
        $instance->{'user'}                = $user;
        $instance->{'chatRoom'}            = $chatRoom;
        $instance->{'userChatIsAnonymous'} = is_null($userChatIsAnonymous) ? $userChatIsAnonymous : (bool) $userChatIsAnonymous;
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
