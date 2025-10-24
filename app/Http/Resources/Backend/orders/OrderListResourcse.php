<?php

namespace App\Http\Resources\Backend\orders;

use App\Http\Resources\Backend\cart\CartResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResourcse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numberOrder' => $this->numberOrder,
            'note' => $this->note,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'cart_id' => $this->cart_id,
            'table_id' => $this->table_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'cart' => new CartResource($this->whenLoaded('cart')),
        ];
    }
}
