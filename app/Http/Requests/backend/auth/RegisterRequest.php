<?php

namespace App\Http\Requests\backend\auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|between:4,50|unique:users,username',
            'email' => 'nullable|string|email|max:100|unique:users,email',
            'primary_phone' => 'required|string|between:8,20|unique:users,primary_phone',
            'secondary_phone' => 'nullable|string|between:8,20|unique:users,secondary_phone',
            'password' => 'required|string|min:8|max:255|confirmed',
            'role' => 'required|string|in:admin',
            'status' => 'sometimes|string|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'This username is already taken',
            'username.between' => 'Username must be between 4 and 50 characters',
            'email.unique' => 'This email is already registered',
            'primary_phone.unique' => 'This phone number is already registered',
            'password.confirmed' => 'Password confirmation does not match',
            'password.min' => 'Password must be at least 8 characters',
        ];
    }

}

