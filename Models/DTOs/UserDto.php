<?php

namespace CCS\Models\DTOs;

abstract class UserDto
{
    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $email = null;
    protected ?string $role = null;
}
