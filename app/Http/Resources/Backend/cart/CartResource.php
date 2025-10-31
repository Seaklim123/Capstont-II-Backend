<?php

namespace App\Http\Resources\Backend\cart;

use App\Http\Resources\Backend\products\ProductResource;
use App\Http\Resources\Backend\tables\TableNumberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'note' => $this->note,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'table_id' => $this->table_id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'table_number' => new TableNumberResource($this->whenLoaded('tableNumber')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
