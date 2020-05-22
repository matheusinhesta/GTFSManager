<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json(['message' => 'Entrou na API']);
});


Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login')->name('login');
Route::post('forget-password', 'AuthController@forgetPassword');

// Authenticated Routes
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@getAuthenticatedUser');
    Route::post('change-password', 'AuthController@changePassword');
});


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
