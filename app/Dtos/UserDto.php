<?php

namespace App\Dtos;

class UserDto
{
    public function __construct(
        public string $username,
        public string $password,
        public ?string $role = null,
        public ?string $status = null,
    ){}
}
