<?php

namespace App\Http\Requests\backend\auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|between:4,20|unique:users,username',
            'password' => 'required|string|between:4,8|confirmed',
            'role' => 'required|string|in:founder_restaurant',
            'status' => 'required|string|in:active,inactive',
        ];
    }
}
