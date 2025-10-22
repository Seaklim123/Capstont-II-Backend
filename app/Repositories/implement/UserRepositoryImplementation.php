<?php

namespace App\Repositories\implement;

use App\Dtos\UserDto;
use App\Exceptions\InvalidCredentialsException;
use App\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    /**
     * @throws InvalidCredentialsException
     */
    public function loginUser(UserDto $userDto): false|User
    {
        $user = $this->findUserByUsername($userDto->username);
        if (!$user || !Hash::check($userDto->password, $user->password)) {
            throw new InvalidCredentialsException('Invalid username or password');
        }
        Auth::login($user);
        return $user;
    }

    public function checkCredentials(UserDto $userDto): User|false
    {
        $user = $this->findUserByUsername($userDto->username);

        if ($user && Hash::check($userDto->password, $user->password)) {
            return $user;
        }
        return false;
    }

}

