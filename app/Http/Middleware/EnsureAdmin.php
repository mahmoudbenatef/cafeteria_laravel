<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::user())
        {
            return response()->json(['status' => "Error", 'data' => "", "message" => "you aren't loggedin"], 401);

        }
        else if (!Auth::user()->isAdmin){
            return response()->json(['status' => "Error", 'data' => "", "message" => "you aren't admin"], 401);
        }
        else {
            return $next($request);

        }
    }
}
