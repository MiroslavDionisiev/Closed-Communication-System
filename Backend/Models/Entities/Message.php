<?php

namespace CCS\Models\Entities;

class Message
{

    private $messageId         = null;
    private $user              = null;
    private $chatRoom          = null;
    private $messageContent    = null;
    private $messageTimestamp  = null;
    private $messageIsDisabled = null;

    public function __construct()
    {
    }

    public static function fill(
        $messageId,
        $user,
        $chatRoom,
        $messageContent,
        $messageTimestamp,
        $messageIsDisabled
    ) {
        $instance = new self();
        $instance->{'messageId'}         = $messageId;
        $instance->{'user'}              = $user;
        $instance->{'chatRoom'}          = $chatRoom;
        $instance->{'messageContent'}    = $messageContent;
        $instance->{'messageTimestamp'}  = $messageTimestamp;
        $instance->{'messageIsDisabled'} = is_null($messageIsDisabled) ? $messageIsDisabled : (bool) $messageIsDisabled;
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
