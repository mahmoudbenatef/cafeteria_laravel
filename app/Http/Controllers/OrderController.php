<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        $this->middleware("auth:sanctum")->except("store");
        $this->middleware("admin")->except("store");
    }

    public function index()
    {

//        return response()->json(['status' => "success", 'data' => RoomResource::collection(Room::all())], 200);

        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'products' => ['required'],
            'room'=> 'required',
            'user_id'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 500);
        }
        else if (sizeof(json_decode($request["products"],true))==0){
            return response()->json(['status' => "Error", 'data' => "", "message" => ["products"=>["order must have products"]]], 500);

        }
        else {
                $order = new Order();
            $order->status="running";
            $order->notes=$request["notes"];
            $order->price=$request["price"];
            $order->room_id=$request["room"];
            $order->user_id=$request["user_id"];
            if ($order->save())
            {
            foreach (json_decode($request["products"],true) as $product){
                $orderItem = new OrderItem();
                $orderItem->order_id =$order->id;
                $orderItem->product_id =$product["id"];
                $orderItem->quantity =$product["quantity"];
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
