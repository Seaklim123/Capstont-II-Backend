<?php

namespace App\Repositories\implement;

use App\Dtos\UserDto;
use App\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepositoryImplementation implements UserRepositoryInterface
{

    public function findUserByUsername(string $username): User
    {
         return User::query()->where('username', $username)->first();
    }

    public function register(UserDto $userDto): User
    {
        $user = UserMapper::userMapper($userDto);
        return User::create($user);
    }
}

