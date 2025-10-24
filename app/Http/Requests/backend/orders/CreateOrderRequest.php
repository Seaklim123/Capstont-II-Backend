<?php

namespace App\Http\Requests\backend\orders;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            'table_id' => 'required|integer',
            'numberOrder'   => 'required|integer',
            'totalPrice'    => 'nullable|numeric|min:0',
            'discount'      => 'nullable|numeric|min:0',
            'status'        => 'required|in:starting,accepted,cancel',
            'payment'       => 'required|in:card,cash',
            'note'          => 'nullable|string',
            'refund'        => 'nullable|numeric|min:0',
            'phone_number'  => 'nullable|string|regex:/^[0-9+\-\s]{8,15}$/',
            'user_id'       => 'nullable|exists:users,id',
        ];
    }
}
