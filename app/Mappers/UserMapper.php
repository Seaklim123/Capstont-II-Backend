<?php

namespace App\Mappers;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserMapper
{
    public static function toModel(UserDto $userDto): User
    {
        return new User([
            'username' => $userDto->username,
            'email' => $userDto->email,
            'primary_phone' => $userDto->primary_phone,
            'secondary_phone' => $userDto->secondary_phone,
            'password' => Hash::make($userDto->password),
            'role' => $userDto->role,
            'status' => $userDto->status,
        ]);
    }

//    public static function toDto(User $user, string $password = ''): UserDto
//    {
//        return new UserDto(
//            username: $user->username,
//            password: $password,
//            primary_phone: $user->primary_phone,
//            email: $user->email,
//            secondary_phone: $user->secondary_phone,
//            role: $user->role,
//            status: $user->status
//        );
//    }
}
