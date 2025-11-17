<?php

namespace App\Services\Interface;

use App\Dtos\UserDto;
use App\Models\User;
use Illuminate\Support\Collection;

interface UserServiceInterface
{
    /**
     * Register a new admin user
     *
     * @param UserDto $userDto
     * @return User
     */
    public function register(UserDto $userDto): User;

    /**
     * Login user and generate authentication token
     *
     * @param string $username
     * @param string $password
     * @return string|null
     */
    public function login(string $username, string $password): ?string;

    /**
     * Create a new cashier user
     *
     * @param UserDto $userDto
     * @return User
     */
    public function createCashier(UserDto $userDto): User;

    /**
     * Get all users
     *
     * @return Collection
     */
    public function getAllUsers(): Collection;

    /**
     * Get user by ID
     *
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User;

    /**
     * Update user by ID
     *
     * @param UserDto $userDto
     * @param int $id
     * @return User
     */
    public function updateUser(UserDto $userDto, int $id): User;

    /**
     * Delete user by ID
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool;

    /**
     * Find user by username
     *
     * @param string $username
     * @return User
     */
    public function findUserByUsername(string $username): User;

    /**
     * Get all active users
     *
     * @return Collection
     */
    public function getActiveUsers(): Collection;

    /**
     * Get all admin users
     *
     * @return Collection
     */
    public function getAdminUsers(): Collection;

    /**
     * Get all cashier users
     *
     * @return Collection
     */
    public function getCashierUsers(): Collection;
}
