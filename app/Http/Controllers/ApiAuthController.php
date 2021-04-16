<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use function Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
class ApiAuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'photo' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 401);
        }

        $input = $request->all();
        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->photo = $input['photo'];
        $user->password = Hash::make($input['password']);
        $user->save();
        return response()->json(['status' => "success", 'data' => "", "message" => "user saved successfully"], 200);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->token = $user->createToken($request->email)->plainTextToken;
        return response()->json(['status' => "success", "message" => "user saved successfully","user"=>$user], 200);
    }

}
