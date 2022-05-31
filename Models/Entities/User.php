<?php

namespace CCS\Models\Entities;

abstract class User
{
    protected ?string $id = null;
    protected ?string $name = null;
    protected ?string $email = null;
    protected ?string $password = null;
    protected ?string $role = null;
}
