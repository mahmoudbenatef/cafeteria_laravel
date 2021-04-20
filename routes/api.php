<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register',[\App\Http\Controllers\ApiAuthController::class,'register']);
Route::post('/login',[\App\Http\Controllers\ApiAuthController::class,'login']);
Route::apiResource('room', 'App\Http\Controllers\RoomController');
Route::apiResource('order', 'App\Http\Controllers\OrderController');
Route::apiResource('product', 'App\Http\Controllers\ProductController');
Route::apiResource('category','App\Http\Controllers\CategoyController');
Route::apiResource('user','App\Http\Controllers\UserController');


