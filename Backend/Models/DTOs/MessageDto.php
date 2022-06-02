<?php

namespace CCS\Models\DTOs;

class MessageDto implements \JsonSerializable
{

    protected $messageId         = null;
    protected $user              = null;
    protected $chatRoom          = null;
    protected $messageContent    = null;
    protected $messageTimestamp  = null;
    protected $messageIsDisabled = null;

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

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function ($val) {
            return !is_null($val);
        });
    }
}
