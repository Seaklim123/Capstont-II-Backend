<?php

namespace App\Services\implementation;

use App\Dtos\UserDto;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\implement\UserRepositoryImplementation;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserServiceImplementation implements UserServiceInterface
{

    protected UserRepositoryImplementation $userRepository;

    public function __construct(UserRepositoryImplementation $userRepository)
    {
        $this->userRepository = $userRepository;
    }



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

    /**
     * @throws UserNotFoundException
     */
    public function loginUser(string $username, string $password): ?string
    {
        $user = $this->userRepository->findUserByUsername($username);
        if (!$user || !Hash::check($password, $user->password)){
            return null;
        }
        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * @throws UserNotFoundException
     */
    public function findUserByUsername(string $username): ?User {
        return $this->userRepository->findUserByUsername($username);
    }
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAllUsers();
    }

    /**
     * @throws UserNotFoundException
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }

    /**
     * @throws UserNotFoundException
     */
    public function updateUserById(UserDto $userDto, int $id): ?User
    {
       return $this->userRepository->updateUserById($userDto, $id);
    }

    /**
     * @throws UserNotFoundException
     */
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
