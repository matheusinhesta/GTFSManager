<?php

use Illuminate\Support\Facades\Route;

// Public Routes
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

        // Agency fares
        Route::resource('fares', 'FareAttributeController');

        // Agency calendar_dates
        Route::resource('calendar-dates', 'CalendarDateController');

        // Agency services
        Route::resource('services', 'ServiceController');

        // Agency zones
        Route::resource('zones', 'ZoneController');

        // Agency fare rules
        Route::resource('fares/{fare_id}/rules', 'FareRuleController');

        // Agency trips
        Route::resource('trips', 'TripController');

        // Agency stops
        Route::resource('stops', 'StopController');

        // Agency stop times
        Route::resource('stop-times', 'StopTimesController');


        // ------------ sem documentação
        // Agency vehicles
        Route::resource('vehicles', 'VehicleController');

        Route::get('completed-trips', 'TripDescriptorController@completedTrips');
        Route::resource('trips-in-progress', 'TripDescriptorController');

    });

    // ------------ sem documentação
    Route::prefix('driver')->group(function(){
        Route::get('trips-to-start', 'DriverController@tripsToStart');
        Route::post('start-trip', 'DriverController@startTrip');
        Route::post('stop-trip', 'DriverController@stopTrip');
        Route::post('cancel-trip', 'DriverController@cancelTrip');
        Route::post('realtime-position', 'DriverController@realtimePosition');
    });

});
