<?php

namespace App\Http\Requests\backend\users;

use Illuminate\Foundation\Http\FormRequest;

class CashierRequest extends FormRequest
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
            'role' => 'required|string|in:cashier',
            'status' => 'sometimes|string|in:active,inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a valid string.',
            'username.between' => 'Username must be between 4 and 50 characters.',
            'username.unique' => 'This username is already taken.',

            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email must not exceed 100 characters.',
            'email.unique' => 'This email is already in use.',

            'primary_phone.required' => 'Primary phone number is required.',
            'primary_phone.string' => 'Primary phone number must be a valid string.',
            'primary_phone.between' => 'Primary phone number must be between 8 and 20 characters.',
            'primary_phone.unique' => 'This primary phone number is already registered.',

            'secondary_phone.string' => 'Secondary phone number must be a valid string.',
            'secondary_phone.between' => 'Secondary phone number must be between 8 and 20 characters.',
            'secondary_phone.unique' => 'This secondary phone number is already registered.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.max' => 'Password must not exceed 255 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'role.required' => 'Role is required.',
            'role.in' => 'Only cashier role is allowed for this endpoint.',

            'status.in' => 'Status must be either active or inactive.',
        ];
    }

}
