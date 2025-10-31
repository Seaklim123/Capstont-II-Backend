<?php

namespace App\Http\Resources\Backend\orders;

use App\Http\Resources\Backend\products\ProductResource;
use App\Http\Resources\Backend\tables\TableNumberResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CheackOrderResourcse extends JsonResource
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
                'totalPrice' => $this->totalPrice,
                'discount' => $this->discount,
                'status' => $this->status,
                'payment' => $this->payment,
                'refund' => $this->refund,
                'priceperorder' => $this->priceperorder ?? 0,
                'phonenumber' => $this->phone_number,
                'order_lists' => OrderListResourcse::collection($this->whenLoaded('orderLists')),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];

    }
}
