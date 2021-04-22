<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderItem;
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

        $this->middleware("auth:sanctum")->except("index");
        $this->middleware("admin")->except("index");
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
            'products' => 'required',
            'room' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 401);
        } else {
            $order = new Order();
            $order->status = "running";
            $order->notes = $request["notes"];
            $order->price = $request["price"];
            $order->room_id = $request["room"];
            $order->user_id = 2;
            if ($order->save()) {
                foreach (json_decode($request["products"], true) as $product) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->product_id = $product["id"];
                    $orderItem->quantity = $product["quantity"];
                    $orderItem->save();
                }
                return response()->json(['status' => "done", 'data' => "", "message" => $order], 200);
            }
//            foreach (json_decode($request["products"],true) as $product){
//                $cat = new Category();
//                $cat->name = $product["name"];
//                $cat->save();
//            }
        }

        //
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
