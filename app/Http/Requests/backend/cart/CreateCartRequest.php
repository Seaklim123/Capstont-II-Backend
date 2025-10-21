<?php

namespace App\Http\Requests\backend\cart;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
           'note' => 'nullable|string|max:255',
            'quantity' => 'required|integer|min:1',
            'product_id' => 'required|exists:products,id',
            'table_id' => 'required|exists:table_numbers,id',
            'status' => 'nullable|in:starting,ordering',
        ];
    }
}
