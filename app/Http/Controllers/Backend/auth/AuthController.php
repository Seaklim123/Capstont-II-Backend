<?php

namespace App\Http\Controllers\Backend\auth;

use App\Dtos\UserDto;
use App\Exceptions\InvalidRoleException;
use App\Exceptions\UserNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Auth\LoginRequest;
use App\Http\Requests\Backend\Auth\RegisterRequest;
use App\Http\Resources\Backend\Auth\AuthResource;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    public function __construct(
        protected UserServiceInterface $userService
    ) {}

    /**
     * Register a new admin user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        try {
            // Basic validation
            $validatedData = $request->validate([
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6',
                'password_confirmation' => 'required|same:password',
                'primary_phone' => 'required|string',
                'secondary_phone' => 'nullable|string',
                'role' => 'required|string|in:admin,cashier',
                'status' => 'required|string|in:active,inactive'
            ]);

            $userDto = new UserDto(
                username: $validatedData['username'],
                email: $validatedData['email'],
                password: $validatedData['password'],
                primary_phone: $validatedData['primary_phone'],
                secondary_phone: $validatedData['secondary_phone'] ?? null,
                role: $validatedData['role'],
                status: $validatedData['status']
            );

            $user = $this->userService->store($userDto);

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data' => [
                    'user' => new AuthResource($user),
                ],
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (InvalidRoleException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Login user and generate token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            // Basic validation
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $token = $this->userService->login(
                $request->input('username'),
                $request->input('password')
            );

            $user = $this->userService->findUserByUsername($request->input('username'));

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => new AuthResource($user),
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);

        } catch (UserNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials - user not found',
            ], 401);

        } catch (UnauthorizedException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage(),
                'debug_info' => [
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    /**
     * Logout user (revoke all tokens)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            // Revoke all tokens for the user
            $request->user()->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Logged out successfully',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get current authenticated user profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function profile(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'message' => 'Profile retrieved successfully',
                'data' => new AuthResource($user),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Refresh user token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function refreshToken(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            // Create new token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'data' => [
                    'token' => $token,
                    'token_type' => 'Bearer',
                ],
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to refresh token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update current user's password
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            $user = $request->user();

            // Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                ], 400);
            }

            // Update password
            $user->password = Hash::make($request->new_password);
            $user->save();

            // Revoke all tokens for security
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully. Please login again.',
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
                'message' => 'Failed to change password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Simple hello endpoint for testing
     *
     * @return JsonResponse
     */
    public function hello(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Hello from Auth Controller!',
            'timestamp' => now(),
        ], 200);
    }
}