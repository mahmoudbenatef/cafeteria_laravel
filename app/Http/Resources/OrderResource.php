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

        $order = [];
        $order['created_at'] = $this->created_at;
        $order['price'] = $this->price;
        $order['room'] = $this->room->number;
        $order['user'] = $this->user->name;
        $order['products'] = [];
        $order['products'] = $this->products->map(function ($product) {
            return [
                'photo' => $product->photo,
                'quantity' => $product->pivot->quantity,
            ];
        });

        return $order;

    }
}
