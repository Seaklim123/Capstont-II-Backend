<?php

namespace App\Mappers;

use App\Dtos\UserDto;
use App\Models\User;

class UserMapper
{
    public static function userMapper(UserDto $userDto): User {
        return new User([
            'username' => $userDto->username,
            'password' => $userDto->password,
            'role' => $userDto->role,
            'status' => $userDto->status,
        ]);
    }
}
