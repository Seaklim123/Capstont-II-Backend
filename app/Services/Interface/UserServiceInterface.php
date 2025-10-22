<?php

namespace App\Services\Interface;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    public function registerUser(UserDto $userDto): User;
    public function loginUser(string $username, string $password): ?string;

    public function getAllUser(): Collection;

    public function getUserById(int $id): ?User;

    public function updateUserById(UserDto $userDto, int $id): ?User;

    public function deleteUserById(int $id): ?User;

    public function createCashier(UserDto $userDto): User;
}
