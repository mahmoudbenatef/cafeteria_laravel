<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
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
        return response()->json(['status' => "success", 'data' => RoomResource::collection(Room::all())], 200);
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
            'number' => 'integer|required|unique:rooms,number',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 500);
        } else {
            $room = new Room();
            $room->number = $request->number;
            if ($room->save()) {
                return response()->json(['status' => "success", 'data' => $room,], 200);
            } else {
                return response()->json(['status' => "Error", 'data' => "", "message" => "something went wrong"], 500);
            }
        }

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
        $validator = Validator::make($request->all(), [
            'number' => 'integer|required|unique:rooms,number,' . $id,
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 500);
        } else {
            $room = Room::find($id);
            $room->number = $request->number;
            if ($room->save()) {
                return response()->json(['status' => "success", 'data' => $room], 200);
            } else {
                return response()->json(['status' => "Error", 'data' => "", "message" => "something went wrong"], 500);
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = DB::table('users')
            ->where('room_id', $id)->count();
        //
    }

    public function displayAllRooms()
    {
        $rooms = Room::orderBy('created_at', 'desc')->paginate(5);
        // dd($rooms);
        return response()->json(['status' => "success", 'data' => $rooms], 200);
    }
}
