<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use function Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
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
        /*
         *         $fileName = time().'.'.$request->file->getClientOriginalExtension();


*/
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            //            'photo' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'ext' => 'required',
            'room_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 200);
        }

        $input = $request->all();
        $date = Carbon::now()->micro;
        $user = new User();
        //        if ($request->file('photo')!=null) {
        //            $request->file('photo')->storeAs(
        //                'public/images', $date . '.jpg'
        //            );
        //            $user->photo = $date . '.jpg';
        //        }
        $file = $request->file('photo');
        $name = '/avatars/' . uniqid() . '.' . $file->extension();
        $file->storePubliclyAs('public', $name);

        $user->name = $input['name'];
        $user->ext = $input['ext'];
        $user->email = $input['email'];
        $user->photo = $name;
        $user->room_id = $input['room_id'];
        $user->password = Hash::make($input['password']);
        $user->save();
        return response()->json(['status' => "success", 'data' => "", "message" => "user saved successfully"], 200);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => "Error", 'data' => "", "message" => $validator->errors()], 401);
        }


        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => "Error", 'data' => "", "message" => ["invalid_credentials" => "The provided credentials are incorrect"]], 401);

            //            throw ValidationException::withMessages([
            //                'email' => ['.'],
            //            ]);
        }

        $user->token = $user->createToken($request->email)->plainTextToken;
        return response()->json(['status' => "success", "message" => "user logged in successfully", "user" => new UserResource($user)], 200);
    }
}
