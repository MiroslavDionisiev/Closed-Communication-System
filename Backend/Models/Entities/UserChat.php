<?php

namespace CCS\Models\Entities;

class UserChat
{

    private $userChatId          = null;
    private $user                = null;
    private $chatRoom            = null;
    private $userChatIsAnonymous = null;
    private $userChatLastSeen    = null;
    private $userChatHasResponded = null;

    public function __construct()
    {
    }

    public static function fill(
        $userChatId,
        $user,
        $chatRoom,
        $userChatIsAnonymous,
        $userChatLastSeen,
        $userChatHasResponded
    ) {
        $instance = new self();
        $instance->{'userChatId'}          = $userChatId;
        $instance->{'user'}                = $user;
        $instance->{'chatRoom'}            = $chatRoom;
        $instance->{'userChatIsAnonymous'} = $userChatIsAnonymous;
        $instance->{'userChatLastSeen'}    = $userChatLastSeen;
        $instance->{'userChatHasResponded'}        = $userChatHasResponded;
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
