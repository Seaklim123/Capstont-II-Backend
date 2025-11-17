<?php

namespace App\Dtos;

class UserDto
{
    public function __construct(
        public string $username,
        public string $password,
        public string $primary_phone,
        public ?string $email = null,
        public ?string $secondary_phone = null,
        public string $role = 'admin',
        public string $status = 'active'
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            username: $data['username'],
            password: $data['password'],
            primary_phone: $data['primary_phone'],
            email: $data['email'] ?? null,
            secondary_phone: $data['secondary_phone'] ?? null,
            role: $data['role'] ?? 'admin',
            status: $data['status'] ?? 'active'
        );
    }
}
