<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'GTFS Manager check!']);
});


Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login')->name('login');
Route::post('forget-password', 'AuthController@forgetPassword');

// Authenticated Routes
Route::middleware(['jwt.verify'])->group(function () {

    // Auth
    Route::post('logout', 'AuthController@logout');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('me', 'AuthController@getAuthenticatedUser');
    Route::post('change-password', 'AuthController@changePassword');

    // User agency informations
    Route::get('agency', 'AgencyController@index');

    // Only admins
    Route::middleware(['permissions.verify'])->group(function () {

        // Edit agency informations
        Route::post('agency', 'AgencyController@update');

        // Agency users
        Route::resource('users', 'UserController');

        // Agency routes
        Route::resource('routes', 'RouteController');


//Route::resource('agency', 'AgencyController')->only(['index', 'update']);
    });

});
