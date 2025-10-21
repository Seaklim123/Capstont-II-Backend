<?php

namespace App\Services\implementation;

use App\Dtos\UserDto;
use App\Mappers\UserMapper;
use App\Models\User;
use App\Repositories\implement\UserRepositoryImplementation;
use App\Services\Interface\UserServiceInterface;

class UserServiceImplementation implements UserServiceInterface
{
    protected UserRepositoryImplementation $userRepository;

    public function __construct(UserRepositoryImplementation $userRepository){
        $this->userRepository = $userRepository;
    }
    public function findUserByUsername(string $username): ?User
    {
        return $this->userRepository->findUserByUsername($username);
    }

    public function register(UserDto $userDto): User
    {
        $user = UserMapper::userMapper($userDto);
        $user->save();
        return $user;
    }
}
