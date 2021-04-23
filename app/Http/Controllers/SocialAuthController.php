<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\SocialAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use function Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }
    public function handleProviderCallback($provider)
    {

        $user = Socialite::driver($provider)->stateless()->user();
        $appUser=User::whereEmail($user->email)->first();
        // dd( $user);
        if(!$appUser){
            $socialUser = new User();               
            $socialUser->name = $user->nickname;
            $socialUser->email = $user->email;
            $socialUser->password = "12345";
            $socialUser->ext="default";
            $socialUser->room_id=1;
            $socialUser->photo = $user->avatar;
            // dd( $user->avatar);
            $socialUser->save();
            $socialAcc=SocialAccount::create([
                'provider'=>$provider,
                'provider_user_id'=>$user->id,
                'user_id'=>$socialUser->id
            ]);

        }else{
            //already registered with that email 
            $socialAcc=$appUser->socialAccounts()->where('provider',$provider);
            // dd("found");
            if(!$socialAcc){
                $socialAcc=SocialAccount::create([
                    'provider'=>$provider,
                    'provider_user_id'=>$user->id,
                    'user_id'=>$appUser->id
                ]);
            }
        }
         $appUser->token = $appUser->createToken($appUser->email)->plainTextToken;
        return response()->json(['status' => "success", "message" => "user logged in successfully", "user" => new UserResource($appUser)], 200);
       
    }
}
