<?php

namespace App\Repositories\Interfaces;

use App\Dtos\UserDto;
use App\Models\User;

interface UserRepositoryInterface
{
    public function findUserByUsername(string $username): ?User;
    public function register(UserDto $userDto): User;

    public function loginUser(UserDto $userDto): false|User;

}
