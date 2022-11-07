<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassportAuthController;

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
Route::group(['prefix' => 'v1'], function () {
    Route::post('register',[PassportAuthController::class,'registerUser']);
    Route::post('login',[PassportAuthController::class,'loginUser']);
    Route::get('logout',[PassportAuthController::class,'logoutUser']);
    Route::middleware('auth:api')->group(function(){
        Route::get('user', [PassportAuthController::class,'authenticatedUserDetails']);
    });
});
//add this middleware to ensure that every request is authenticated