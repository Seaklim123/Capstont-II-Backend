<?php

namespace App\Services\Interface;

use App\Dtos\UserDto;
use App\Models\User;

interface UserServiceInterface
{
    public function findUserByUsername(string $username): ?User;

    public function register(UserDto $userDto): User;
}
