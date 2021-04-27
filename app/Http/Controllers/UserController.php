<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use function Illuminate\Support\Facades\Hash;


use Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware("auth:sanctum")->except("getMyOrders","getMyFilteredOrders","index","update",);
        $this->middleware("admin")->except("getMyOrders","getMyFilteredOrders","index","update",);
    }

    public function index()
    {

        return response()->json(['status' => "success", 'data' => UserResource::collection(User::all())], 200);

        //
    }
    public function destroy($id)
    {

        $user=User::where('id','=',$id)->first();
        
        
            if ($user!=null&&$user->delete())
            {
                return response()->json(['status' => "success", "data"=> "ay haga"], 200);
            }
       
            return response()->json(['status' => "Error", 'data' => "","message"=>"something went wrong"], 401);  
        
    }

    public function getMyOrders($id)
    {
        $orders = Order::where('user_id', $id)->get();
        return response()->json(['status' => "success", 'data' => $orders], 200);
    }

    public function getMyFilteredOrders(Request $request, $id)
    {
        $orders = Order::where('user_id', $id)->whereBetween('created_at', [$request->query('from'), $request->query('to')])->get();
        return response()->json(['status' => 'success', 'data' => $orders], 200);
    }

    public function update(Request $request, User $user)
    {
        error_log('Some message here.');

        $validator = Validator::make($request->all(), [
            // 'name' => 'string',
            //            'photo' => 'required',
            // 'email' => 'string|unique:users,email',
            
            // 'password' => 'required',
            // 'c_password' => 'required|same:password',
            // 'ext' => 'string',
            // 'room_id' => 'required',
        ]);
        // check validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 200);
        } else {
            if ($request->hasFile("photo")) {
                $file = $request->file('photo');
                error_log($file);

                $name = 'users/' . uniqid() . '.' . $file->extension();
                $file->storePubliclyAs('public', $name);
                $user->photo =  asset('storage/' . ($name));
            }
            $user->name = $request->name ? $request->name : $user->name;
            $user->email = $request->email ? $request->email : $user->email;
            $user->password = $request->password  ? Hash::make( $request->password) : $user->password;
            $user->ext = $request->ext ? $request->ext : $user->ext;
             $user->room_id = $request->room_id ? $request->room_id : $user->room_id;



                    error_log($user);

            return $user->update() ?

                response()->json(["status" => "success by kaoud", "data" => $user], 200) :
                
                response()->json(['status' => "error", "message" => "request failed"], 403);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
}
