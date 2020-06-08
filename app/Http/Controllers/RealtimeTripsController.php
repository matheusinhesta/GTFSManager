<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Route;
use App\Models\TripDescriptor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RealtimeTripsController extends Controller {

    public function nextTrips($agency_id){
        Agency::findOrFail($agency_id);
        $routes_id  = Route::where('agency_id', $agency_id)->pluck('id');
        $trips_descriptors = TripDescriptor::with('user', 'user.user_type', 'trip', 'route')
                                    ->whereIn('route_id', $routes_id)->whereIn('trip_status', ['scheduled', 'started', 'canceled'])
                                    ->whereBetween('start_date', [Carbon::now()->subMinute(5)->format('Y-m-d'), Carbon::now()->addMinutes(25)->format('Y-m-d')])
                                    ->whereBetween('start_time', [Carbon::now()->subMinute(5)->format('H:i:s'), Carbon::now()->addMinutes(25)->format('H:i:s')])
                                    ->orderBy('start_date')->orderBy('start_time')
                                    ->get(['id', 'user_id', 'trip_id', 'route_id', 'trip_status', 'start_date', 'start_time', 'end_date', 'end_time', 'created_at', 'updated_at']);

        $trips_descriptors->each->setAppends(['nearby_stop', 'next_stop', 'vehicle_informations']);

        return response()->json(compact('trips_descriptors'), 200);
    }


    public function realtimeTrip($agency_id, $trip_descriptor_id, Request $request){
        Agency::findOrFail($agency_id);
        $routes_id       = Route::where('agency_id', $agency_id)->pluck('id');
        $trip_descriptor = TripDescriptor::with('user', 'user.user_type', 'trip', 'trip.service', 'route')
                                    ->with(['route.fare_rules' => function($q){
                                        $q->first();
                                    }])
                                    ->with(['vehicle_positions' => function($q) use ($request){
                                        if($request->limit)
                                            $q->limit($request->limit);
                                    }])
                                    ->select(['id', 'user_id', 'trip_id', 'route_id', 'trip_status', 'start_date', 'start_time', 'end_date', 'end_time', 'created_at', 'updated_at'])
                                    ->whereIn('route_id', $routes_id)->whereIn('trip_status', ['scheduled', 'started', 'canceled'])
                                    ->findOrFail($trip_descriptor_id);

        $trip_descriptor->setAppends(['nearby_stop', 'next_stop', 'current_position', 'vehicle_informations']);

        return response()->json(compact('trip_descriptor'), 200);
    }

}
