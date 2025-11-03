<?php

namespace App\Http\Controllers\Backend\user;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\backend\users\CashierRequest;
use App\Http\Requests\backend\users\UpdateUserRequest;
use App\Http\Resources\Backend\users\UserResource;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{

    public function __construct(private UserServiceInterface $userService){}
    public function sayhell(): string {
        return 'Hello World';
    }
    /**
     * Display a listing of the resource.
     */
    public function index() : JsonResponse
    {
        $user = $this->userService->getAllUsers();
        return response()->json([
            'message' => 'Get all user',
            'data' => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function cashier(CashierRequest $request): JsonResponse
    {
        $userDto = new UserDto(...$request->validated());
        $user = $this->userService->createCashier($userDto);
        return response()->json([
            'message' => 'Create cashier',
            'data' => new UserResource($user)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);
        return response()->json([
            'message' => 'Get user by id',
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, int $id): JsonResponse
    {
        $userModelUpdate = new UserDto(...$request->validated());
        $user = $this->userService->updateUserById($userModelUpdate, $id);
        return response()->json([
            'message' => 'Update user by id',
            'data' => new UserResource($user)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        $this->userService->deleteUserById($id);
        return response()->json([
            'message' => 'Delete user successfully',
        ]);
    }
}
