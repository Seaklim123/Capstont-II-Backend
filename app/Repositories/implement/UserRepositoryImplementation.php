<?php

namespace App\Repositories\implement;

use App\Dtos\UserDto;
use App\Exceptions\UserNotFoundException;
use App\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Collection;

class UserRepositoryImplementation implements UserRepositoryInterface
{


    public function registerUser(UserDto $userDto): User
    {
        $user = UserMapper::userMapper($userDto);
        $user->save();
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByUsername(string $username): ?User
    {
        $user = User::where("username", $username)->first();
        if(!$user){
            throw new UserNotFoundException('User not found');
        }
        return $user;
    }

    public function getAllUsers(): Collection
    {
        return User::all();
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUserById(int $id): ?User
    {
        $user = User::where("id", $id)->first();
        if(!$user){
            throw new UserNotFoundException('User not found');
        }
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function updateUserById(UserDto $userDto, int $id): ?User
    {
        $user = User::where("id", $id)->first();
        if(!$user){
            throw new UserNotFoundException("User not found");
        }
        $userModelUpdate = UserMapper::userMapper($userDto);
        $user->update($userModelUpdate->getAttributes());
        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function deleteUserById(int $id): ?User
    {
        $user = User::where("id", $id)->first();
        if(!$user){
            throw new UserNotFoundException("Invalid with User ID");
        }
        $user->delete();
        return $user;
    }


    public function createCashier(UserDto $userDto): User{
        $user = UserMapper::userMapper($userDto);
        $user->save();
        return $user;
    }

}
