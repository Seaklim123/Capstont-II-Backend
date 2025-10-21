<?php

namespace App\Http\Requests\backend\cart;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'note' => 'nullable|string|max:255',
            'quantity' => 'sometimes|integer|min:1',
            'product_id' => 'sometimes|exists:products,id',
            'table_id' => 'sometimes|exists:table_numbers,id',
            'status' => 'nullable|in:starting,ordering',

        ];
    }
}
