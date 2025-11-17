<?php

namespace App\Services\implementation;

use App\Dtos\UserDto;
use App\Exceptions\InvalidRoleException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class UserServiceImplementation implements UserServiceInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    /**
     * Register a new admin user
     *
     * @param UserDto $userDto
     * @return User
     * @throws InvalidRoleException
     */
    public function register(UserDto $userDto): User
    {
        // Create a temporary user instance to check role using helper method
        $tempUser = new User(['role' => $userDto->role]);

        if ($tempUser->isCashier()) {
            throw new InvalidRoleException('Cashier cannot be registered through this endpoint');
        }

        return $this->userRepository->create($userDto);
    }

    /**
     * Login user and generate token
     *
     * @param string $username
     * @param string $password
     * @return string|null
     * @throws UnauthorizedException
     */
    public function login(string $username, string $password): ?string
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user || !Hash::check($password, $user->password)) {
            throw new UnauthorizedException('Invalid credentials');
        }

        // Use helper method to check if user is active
        if (!$user->isActive()) {
            throw new UnauthorizedException('User account is inactive');
        }

        return $user->createToken('auth_token')->plainTextToken;
    }

    /**
     * Create a new cashier user
     *
     * @param UserDto $userDto
     * @return User
     * @throws InvalidRoleException
     */
    public function createCashier(UserDto $userDto): User
    {
        // Create a temporary user instance to check role using helper method
        $tempUser = new User(['role' => $userDto->role]);

        if (!$tempUser->isCashier()) {
            throw new InvalidRoleException('Only cashier role can be created through this endpoint');
        }

        return $this->userRepository->create($userDto);
    }

    /**
     * Get all users
     *
     * @return Collection
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Update user by ID
     *
     * @param UserDto $userDto
     * @param int $id
     * @return User
     */
    public function updateUser(UserDto $userDto, int $id): User
    {
        return $this->userRepository->update($userDto, $id);
    }

    /**
     * Delete user by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        return $this->userRepository->delete($id);
    }

    /**
     * Find user by username
     *
     * @param string $username
     * @return User
     */
    public function findUserByUsername(string $username): User
    {
        return $this->userRepository->findByUsername($username);
    }

    /**
     * Get all active users
     *
     * @return Collection
     */
    public function getActiveUsers(): Collection
    {
        return $this->userRepository->getAll()->filter(function (User $user) {
            return $user->isActive(); // Use helper method
        });
    }

    /**
     * Get all admin users
     *
     * @return Collection
     */
    public function getAdminUsers(): Collection
    {
        return $this->userRepository->getAll()->filter(function (User $user) {
            return $user->isAdmin(); // Use helper method
        });
    }

    /**
     * Get all cashier users
     *
     * @return Collection
     */
    public function getCashierUsers(): Collection
    {
        return $this->userRepository->getAll()->filter(function (User $user) {
            return $user->isCashier(); // Use helper method
        });
    }
}
