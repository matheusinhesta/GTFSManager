<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json(['message' => 'Entrou na API']);
});


Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login')->name('login');

// Authenticated Routes
Route::middleware(['jwt.verify'])->group(function () {
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});
