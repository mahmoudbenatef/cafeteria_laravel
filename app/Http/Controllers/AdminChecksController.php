<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminChecksController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth:sanctum");
        $this->middleware("admin");
    }

    public function index(Request $request)
    {
        $user_checks = [];

        if (!is_null($request->query("user_id"))) {
            $all_users = array(User::findOrFail($request->query("user_id")));
        } else {
            $all_users = User::all(["id", "name"]);
        }


        foreach ($all_users as $user) {
            $totalAmount = $user->orders->sum('price');
            $user["totalAmount"] = $totalAmount;

            if (!is_null($request->query("from")) && !is_null($request->query("to"))) {
                $user_orders = $user->orders->whereBetween('created_at', [$request->query("from"), $request->query("to")]);
            } else {
                $user_orders = $user->orders;
            }
            $user["orders"] = $user_orders;


            foreach ($user["orders"] as $order) {
                $order_products = $order->products;
                $order["products"] = $order_products;

            }


            array_push($user_checks, $user);


        }

        if (count($user_checks)) {
            return response()->json(['status' => "success", 'data' => ["checks" => $user_checks, "users" => User::all("id", "name")]], 200);
        } else {
            return response()->json(['status' => "failed"], 400);
        }

    }
}
