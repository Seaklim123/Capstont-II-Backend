<?php

namespace App\Services\implementation;

use App\Dtos\UserDto;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserServiceImplementation implements UserServiceInterface
{


    public function __construct(private UserRepositoryInterface $userRepository){}

    /**
     * @throws UserNotFoundException
     */
    public function registerUser(UserDto $userDto): User
    {
        if($userDto->role === 'cashier'){
            throw new UserNotFoundException('Cashier can not be registered');
        }
        return $this->userRepository->registerUser($userDto);
    }

    public function loginUser(string $username, string $password): ?string
    {
        $user = $this->userRepository->findUserByUsername($username);
        if (!$user || !Hash::check($password, $user->password)){
            return null;
        }
        return $user->createToken('auth_token')->plainTextToken;
    }

    public function getAllUser(): Collection
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    public function updateUserById(UserDto $userDto, int $id): ?User
    {
       return $this->userRepository->updateUserById($userDto, $id);
    }

    public function deleteUserById(int $id): ?User{
        return $this->userRepository->deleteUserById($id);
    }


    /**
     * @throws UserNotFoundException
     */
    public function createCashier(UserDto $userDto): User
    {
        if($userDto->role === 'founder_restaurant'){
            throw new UserNotFoundException('Founder can not be create');
        }
        return $this->userRepository->createCashier($userDto);
    }
}
