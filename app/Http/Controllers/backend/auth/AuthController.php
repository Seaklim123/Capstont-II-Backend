<?php

namespace App\Http\Controllers\backend\auth;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\backend\auth\UserLogin;
use App\Http\Requests\backend\auth\UserRegister;
use App\Http\Resources\Backend\Auth\AuthResource;
use App\Services\implementation\UserServiceImplementation;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected UserServiceImplementation $userService;

    public function __construct(UserServiceImplementation $userService){
        $this->userService = $userService;
    }

    public function register(UserRegister $request): JsonResponse
    {
        $userDto  = new UserDto(...$request->validated());
        $user = $this->userService->register($userDto);
        return response()->json([
            'message' => 'User successfully registered',
            'user' => new AuthResource($user)
        ], status: 201);
    }

    public function login(UserLogin $request): JsonResponse{
        $userDto = new UserDto(
            username: $request->username,
            password: $request->password,
            role: '',
            status: ''
        );
        $user= $this->userService->loginUser($userDto);
        return response()->json([
            'message' => 'User successfully logged in',
            'user' => new AuthResource($user)
        ], 200);
    }

}
