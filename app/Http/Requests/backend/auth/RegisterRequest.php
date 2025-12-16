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
            'primary_phone' => 'required|string|min:9|max:15|unique:users,primary_phone',
            'secondary_phone' => 'nullable|string|min:9|max:15|unique:users,secondary_phone',
            'password' => 'required|string|min:8|max:255|confirmed',
            'role' => 'required|string|in:admin',
            'status' => 'required|string|in:active',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.unique' => 'This username is already taken.',
            'username.between' => 'Username must be between 4 and 50 characters.',

            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered.',
            'email.max' => 'Email must not exceed 100 characters.',

            'primary_phone.required' => 'Primary phone number is required.',
            'primary_phone.min' => 'Primary phone number must be at least 9 characters.',
            'primary_phone.max' => 'Primary phone number must not exceed 15 characters.',
            'primary_phone.unique' => 'This phone number is already registered.',

            'secondary_phone.min' => 'Secondary phone number must be at least 9 characters.',
            'secondary_phone.max' => 'Secondary phone number must not exceed 15 characters.',
            'secondary_phone.unique' => 'This secondary phone number is already registered.',

            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters.',

            'role.in' => 'Only admin role is allowed for registration.',
            'status.in' => 'Status must be active when registering.',
        ];
    }
}
