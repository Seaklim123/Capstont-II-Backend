<?php

namespace App\Http\Resources\Backend\products;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $discount = $this->discount ?? 0;
        $finalPrice = $discount > 0 ? $this->price * (1 - $discount / 100) : $this->price;
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'discount' => $discount,
            'final_price' => round($finalPrice, 2),
            'image' => $this->image ? url($this->image) : null,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'is_best_seller' => $this->is_best_seller ?? false,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
