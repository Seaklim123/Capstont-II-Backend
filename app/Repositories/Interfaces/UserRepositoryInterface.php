<?php

namespace App\Repositories\Interfaces;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function findUserByUsername(string $username): ?User;
    public function registerUser(UserDto $userDto): User;

    public function getAllUsers(): Collection;

    public function getUserById(int $id): ?User;

    public function updateUserById(UserDto $userDto, int $id): ?User;

    public function deleteUserById(int $id): ?User;

    public function createCashier(UserDto $userDto): User;
}
