<?php

namespace App\Dtos;

class UserDto
{
    public function __construct(
        public string $username,
        public string $password,
        public ?string $role = 'founder_restaurant',
        public ?string $status = 'active'
    ){}
}
