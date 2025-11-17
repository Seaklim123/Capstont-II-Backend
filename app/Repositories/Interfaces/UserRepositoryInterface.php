<?php

namespace App\Repositories\Interfaces;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function findByUsername(string $username): ?User;

    public function findById(int $id): ?User;

    public function create(UserDto $userDto): User;

    public function getAll(): Collection;

    public function update(UserDto $userDto, int $id): User;

    public function delete(int $id): bool;
}
