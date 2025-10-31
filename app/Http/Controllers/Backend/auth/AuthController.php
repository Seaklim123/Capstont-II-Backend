<?php

namespace App\Http\Controllers\Backend\auth;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\backend\auth\LoginRequest;
use App\Http\Requests\backend\auth\RegisterRequest;
use App\Http\Resources\Backend\Auth\AuthResource;
use App\Services\Interface\UserServiceInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(protected UserServiceInterface $userService){}

    public function register(RegisterRequest $request): JsonResponse{
        $userDto = new UserDto(...$request->validated());
       $user =  $this->userService->registerUser($userDto);
       return new JsonResponse([
           'message' => 'User successfully registered',
           'user' => new AuthResource($user)
       ], 201);
    }

    public function login(LoginRequest $request): JsonResponse {
        $token = $this->userService->loginUser(
            $request->username,
            $request->password
        );
        if(!$token){
            return new JsonResponse(['message' => 'Unauthorized'], 401);
        }
        return response()->json([
            'message' => 'User successfully logged in',
            'token' => $token
        ]);
    }
}
