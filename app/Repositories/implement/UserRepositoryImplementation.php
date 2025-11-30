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
    /**
     * @throws UserNotFoundException
     */
    public function findByUsername(string $username): ?User
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            throw new UserNotFoundException("User with username '{$username}' not found");
        }

        return $user;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findById(int $id): ?User
    {
        $user = User::find($id);

        if (!$user) {
            throw new UserNotFoundException("User with ID {$id} not found");
        }

        return $user;
    }

    public function create(UserDto $userDto): User
    {
        $user = UserMapper::toModel($userDto);
        $user->save();

        return $user;
    }

    public function getAll(): Collection
    {
        return User::orderBy('created_at', 'desc')->get();
    }

    /**
     * @throws UserNotFoundException
     */
    public function update(UserDto $userDto, int $id): User
    {
        $user = $this->findById($id);

        $userData = UserMapper::toModel($userDto);
        $user->update($userData->getAttributes());

        return $user->fresh();
    }

    /**
     * @throws UserNotFoundException
     */
    public function delete(int $id): bool
    {
        $user = $this->findById($id);

        return $user->delete();
    }
}
