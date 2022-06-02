<?php

namespace CCS\Models\DTOs;

class ResponseDto implements \JsonSerializable
{

    protected $status;
    protected $timestamp;

    public function __construct($status)
    {
        http_response_code($status);
        $this->status    = $status;
        $this->timestamp = date(DATE_ATOM);
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

class ResponseDtoSuccess extends ResponseDto
{
    private $message;

    public function __construct($status, $message)
    {
        parent::__construct($status);
        $this->message = $message;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}

class ResponseDtoError extends ResponseDto
{
    private $error;

    public function __construct($status, $error)
    {
        parent::__construct($status);
        $this->error = $error;
    }

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this), function($val) { return !is_null($val); });
    }
}
