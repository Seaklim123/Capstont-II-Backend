<?php
namespace App\Mappers;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserMapper
{
    public static function userMapper(UserDto $userDto): User
    {
        return new User([
            'username' => $userDto->username,
            'password' => Hash::make($userDto->password),
            'role' => $userDto->role ?? 'founder_restaurant',
            'status' => $userDto->status ?? 'active',
        ]);
    }
}
