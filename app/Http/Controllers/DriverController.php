<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateTripDescriptor;
use App\Models\TripDescriptor;
use App\Models\VehiclePosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DriverController extends Controller {

    public function tripsToStart(){
        $trips = \Auth::user()->trip_descriptors()->with('route', 'trip')->where('trip_status', 'scheduled')->orderBy('start_date')->orderBy('start_time')->get(['id', 'trip_id', 'route_id', 'trip_status', 'start_date', 'start_time', 'end_date', 'end_time', 'created_at', 'updated_at']);
        return response()->json(compact('trips'), 200);
    }

    public function startTrip(Request $request){
        $validator = Validator::make($request->all(), [
            'trip_id'  => 'required|integer|min:0',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $trip = TripDescriptor::whereIn('id', \Auth::user()->trip_descriptors->pluck('id'))->where('trip_status', 'scheduled')->findOrFail($request->trip_id);

        DB::beginTransaction();

        try{
            $trip->update(['trip_status' => 'started']);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message' => "Couldn't start trip!"], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Trip started!'], 200);
    }

    public function closeTrip(Request $request){
        $validator = Validator::make($request->all(), [
            'trip_id'  => 'required|integer|min:0',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $trip = TripDescriptor::whereIn('id', \Auth::user()->trip_descriptors->pluck('id'))->where('trip_status', 'started')->findOrFail($request->trip_id);

        DB::beginTransaction();

        try{
            $trip->update(['trip_status' => 'closed']);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message' => "Couldn't close trip!"], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Trip closed!'], 200);
    }

    public function cancelTrip(Request $request){
        $validator = Validator::make($request->all(), [
            'trip_id'  => 'required|integer|min:0',
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $trip = TripDescriptor::whereIn('id', \Auth::user()->trip_descriptors->pluck('id'))->findOrFail($request->trip_id);

        DB::beginTransaction();

        try{
            $trip->update(['trip_status' => 'canceled']);
        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message' => "Couldn't cancel trip!"], 500);
        }

        DB::commit();

        return response()->json('Trip canceled!', 200);
    }

    public function realtimePosition(Request $request){
        $validator = Validator::make($request->all(), [
            'trip_in_course_id' => 'required|integer|min:0',
            'lat'               => 'string|min:0',
            'lon'               => 'string|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $trip = TripDescriptor::whereIn('id', \Auth::user()->trip_descriptors->pluck('id'))->where('trip_status', 'started')->findOrFail($request->trip_in_course_id);

        DB::beginTransaction();

        try{
            $vehicle_position = VehiclePosition::create(array_merge($request->all(), [
                'trip_descriptor_id' => $trip->id,
                'vehicle_id'         => $trip->trip_updates->first()->vehicle_id
            ]));

            // dispach função que insere no stop_time_update e atualiza o trip_update
            UpdateTripDescriptor::dispatch($trip, $vehicle_position);

        } catch (\Exception $e){
            DB::rollBack();
            return response()->json(['message' => "The current location could not be sent!"], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Position registred!'], 200);
    }

}
