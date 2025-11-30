<?php

namespace App\Http\Requests\backend\users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'username' => "required|string|between:4,50|unique:users,username,{$userId}",
            'email' => "nullable|string|email|max:100|unique:users,email,{$userId}",
            'primary_phone' => "required|string|between:8,20|unique:users,primary_phone,{$userId}",
            'secondary_phone' => "nullable|string|between:8,20|unique:users,secondary_phone,{$userId}",
            'password' => 'required|string|min:8|max:255',
            'role' => 'required|string|in:admin,cashier',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
