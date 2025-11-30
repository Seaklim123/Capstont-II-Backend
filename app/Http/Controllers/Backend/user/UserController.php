<?php

namespace App\Http\Controllers\Backend\user;

use App\Dtos\UserDto;
use App\Exceptions\InvalidRoleException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Users\CashierRequest;
use App\Http\Requests\Backend\Users\UpdateUserRequest;
use App\Http\Resources\Backend\Users\UserResource;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ) {}

    /**
     * Display a listing of all users (Admin only)
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $users = $this->userService->getAllUsers();

            return response()->json([
                'success' => true,
                'message' => 'Users retrieved successfully',
                'data' => UserResource::collection($users),
                'total' => $users->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified user by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $user = $this->userService->getUserById($id);

            return response()->json([
                'success' => true,
                'message' => 'User retrieved successfully',
                'data' => new UserResource($user),
            ], 200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new cashier (Admin only)
     *
     * @param CashierRequest $request
     * @return JsonResponse
     */
    public function cashier(CashierRequest $request): JsonResponse
    {
        try {
            $userDto = UserDto::fromArray($request->validated());
            $user = $this->userService->createCashier($userDto);

            return response()->json([
                'success' => true,
                'message' => 'Cashier created successfully',
                'data' => new UserResource($user),
            ], 201);

        } catch (InvalidRoleException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create cashier',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update user by ID (Admin only)
     *
     * @param UpdateUserRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        try {
            $userDto = UserDto::fromArray($request->validated());
            $user = $this->userService->updateUser($userDto, $id);

            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'data' => new UserResource($user),
            ], 200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete user by ID (Admin only)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            // Prevent deleting yourself
            if (auth()->id() === $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account',
                ], 400);
            }

            $this->userService->deleteUser($id);

            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully',
            ], 200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete user',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all active users (Admin only)
     *
     * @return JsonResponse
     */
    public function activeUsers(): JsonResponse
    {
        try {
            $users = $this->userService->getActiveUsers();

            return response()->json([
                'success' => true,
                'message' => 'Active users retrieved successfully',
                'data' => UserResource::collection($users),
                'total' => $users->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve active users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all admin users (Admin only)
     *
     * @return JsonResponse
     */
    public function admins(): JsonResponse
    {
        try {
            $users = $this->userService->getAdminUsers();

            return response()->json([
                'success' => true,
                'message' => 'Admin users retrieved successfully',
                'data' => UserResource::collection($users),
                'total' => $users->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve admin users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all cashier users (Admin only)
     *
     * @return JsonResponse
     */
    public function cashiers(): JsonResponse
    {
        try {
            $users = $this->userService->getCashierUsers();

            return response()->json([
                'success' => true,
                'message' => 'Cashier users retrieved successfully',
                'data' => UserResource::collection($users),
                'total' => $users->count(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve cashier users',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle user status (active/inactive) (Admin only)
     *
     * @param int $id
     * @return JsonResponse
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            // Prevent deactivating yourself
            if (auth()->id() === $id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot change your own status',
                ], 400);
            }

            $user = $this->userService->getUserById($id);

            // Toggle status
            $newStatus = $user->isActive() ? 'inactive' : 'active';

            $userDto = new UserDto(
                username: $user->username,
                password: $user->password,
                primary_phone: $user->primary_phone,
                email: $user->email,
                secondary_phone: $user->secondary_phone,
                role: $user->role,
                status: $newStatus
            );

            $updatedUser = $this->userService->updateUser($userDto, $id);

            // If deactivated, revoke all user's tokens
            if ($newStatus === 'inactive') {
                $updatedUser->tokens()->delete();
            }

            return response()->json([
                'success' => true,
                'message' => "User status changed to {$newStatus}",
                'data' => new UserResource($updatedUser),
            ], 200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle user status',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get user statistics (Admin only)
     *
     * @return JsonResponse
     */
    public function statistics(): JsonResponse
    {
        try {
            $allUsers = $this->userService->getAllUsers();

            $stats = [
                'total_users' => $allUsers->count(),
                'total_admins' => $allUsers->filter(fn($u) => $u->isAdmin())->count(),
                'total_cashiers' => $allUsers->filter(fn($u) => $u->isCashier())->count(),
                'active_users' => $allUsers->filter(fn($u) => $u->isActive())->count(),
                'inactive_users' => $allUsers->filter(fn($u) => !$u->isActive())->count(),
            ];

            return response()->json([
                'success' => true,
                'message' => 'User statistics retrieved successfully',
                'data' => $stats,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search users by username or email (Admin only)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'query' => 'required|string|min:2',
            ]);

            $query = $request->input('query');
            $allUsers = $this->userService->getAllUsers();

            $filteredUsers = $allUsers->filter(function ($user) use ($query) {
                return stripos($user->username, $query) !== false ||
                    stripos($user->email, $query) !== false;
            });

            return response()->json([
                'success' => true,
                'message' => 'Search completed successfully',
                'data' => UserResource::collection($filteredUsers),
                'total' => $filteredUsers->count(),
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
