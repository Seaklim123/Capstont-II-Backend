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
            'role.in' => 'Only cashier role is allowed for this endpoint',
        ];
    }
}
