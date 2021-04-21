<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $orders = [];
        $orders['id'] = $this->id;
        $orders['created_at'] = $this->created_at;
        $orders['price'] = $this->price;
        $orders['room'] = $this->room->number;
        $orders['user'] = $this->user->name;
        $orders['products'] = [];
        $orders['products'] = $this->products->map(function ($product) {
            return [
                'photo' => $product->photo,
                'quantity' => $product->pivot->quantity,
            ];
        });

        return $orders;

    }
}
