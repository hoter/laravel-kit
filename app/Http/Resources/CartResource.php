<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'total' => $this->when($this->relationLoaded('items'), function () {
                return round($this->items->sum(function ($item) {
                    return $item->relationLoaded('product')
                        ? $item->product->price * $item->quantity
                        : 0;
                }), 2);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
