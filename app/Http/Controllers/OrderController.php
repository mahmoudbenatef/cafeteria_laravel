<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware("auth:sanctum");
        $this->middleware("admin")->except("store");
    }

    public function index()
    {

        $orders = Order::all()->where('status', 'running');


        if ($orders) {
            return response()->json(['status' => "success", 'data' => OrderResource::collection($orders)], 200);
        } else {
            return response()->json(['status' => "failed"], 400);
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => ['required'],
            'room' => 'required',
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 500);
        } else if (sizeof(json_decode($request["products"], true)) == 0) {
            return response()->json(['status' => "Error", 'data' => "", "message" => ["products" => ["order must have products"]]], 500);
        } else {
            $order = new Order();
            $order->status = "running";
            $order->notes = $request["notes"];
            $order->price = $request["price"];
            $order->room_id = $request["room"];
            $order->user_id = $request["user_id"];
            $order->save();

            $products = json_decode($request["products"], true);

            foreach ($products as $product) {
                $order->products()->attach($product["id"], ['quantity' => $product['quantity']]);
            }

            return response()->json(['status' => "done", 'data' => "", "message" => $order], 200);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {

        $order->status = "delivered";

        if ($order->update()) {
            return response()->json(['status' => "success", 'data' => "", "message" => "order updated successfully"], 200);

        } else {
            return response()->json(['status' => "Error", 'data' => "", "message" => "could not update order"], 500);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
