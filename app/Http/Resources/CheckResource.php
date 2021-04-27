<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CheckResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        $from = $request->query("from");
        $to = $request->query("to");

        $user_orders = [];
        $user_orders["id"] = $this->id;
        $user_orders["user"] = $this->name;
        $user_orders["totalAmount"] = $this->orders->sum('price');

        $user_orders["orders"] = !is_null($from) && !is_null($to) ? $this->orders->whereBetween('created_at', [$from, $to]) : $this->orders;

        $user_orders["orders"] = $user_orders["orders"]->map(function ($order) use ($from, $to) {

            return [
                'id' => $order->id,
                'created_at' => $order->created_at,
                'price' => $order->price,
                'orderProducts' => $order->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                        'photo' => $product->photo,
                        'quantity' => $product->pivot->quantity,
                    ];
                }),
            ];

        });

        $user_orders["orders"] = $user_orders["orders"]->toArray();

        return $user_orders;

    }
}
