<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware("auth:sanctum");
        $this->middleware("admin");
    }

    public function index()
    {

        return response()->json(['status' => "success", 'data' => RoomResource::collection(Room::all())], 200);

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
//        dd(Auth::user());

//        dd($request);
        $validator = Validator::make($request->all(), [
            'number' => 'required|unique:rooms,number',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 401);
        }
        else {
            $room = new Room();
            $room->number = $request->number;
            if ($room->save())
            {
                return response()->json(['status' => "success", 'data' => $room], 200);
            }
            else
            {
                return response()->json(['status' => "Error", 'data' => "","message"=>"something went wrong"], 401);

            }
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
