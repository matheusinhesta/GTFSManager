<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\StopTimeUpdate;
use App\Models\Trip;
use App\Models\TripDescriptor;
use App\Models\TripUpdate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TripDescriptorController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trips     = TripDescriptor::whereIn('route_id', $routes_id)->get();
        return response()->json(compact('trips'), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trips_id  = Trip::whereIn('route_id', $routes_id)->pluck('id');

        $validator = Validator::make($request->except('trip_status'), [
            'route_id'   => ['required', 'integer', Rule::in($routes_id)],
            'trip_id'    => ['required', 'integer', Rule::in($trips_id)],
            'user_id'    => ['required', 'integer', Rule::in(\Auth::user()->agency->users()->where('type_id', 2)->pluck('id'))],
            'vehicle_id' => ['required', 'integer', Rule::in(\Auth::user()->agency->vehicle_descriptors->pluck('id'))],
            'start_time' => 'required|string',
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_time'   => 'required|string',
            'end_date'   => 'required|date|date_format:Y-m-d'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $trip = TripDescriptor::create($request->except('trip_status'));

            $stop_time_update = StopTimeUpdate::create([
                'stop_sequence' => $trip->trip->stop_times()->orderBy('stop_sequence')->first()->id,
                'stop_id' => $trip->trip->stop_times()->orderBy('stop_sequence')->first()->stop_id,
            ]);

            TripUpdate::create([
                'trip_descriptor_id' => $trip->id,
                'vehicle_id' => $request->vehicle_id,
                'stop_time_update_id' => $stop_time_update->id
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create trip. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The trip has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trip = TripDescriptor::with('trip', 'route', 'user')->whereIn('route_id', $routes_id)->findOrFail($id);
        return response()->json(compact('trip'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trips_id  = Trip::whereIn('route_id', $routes_id)->pluck('id');
        $trip      = TripDescriptor::whereIn('route_id', $routes_id)->findOrFail($id);

        if($trip->trip_status != 'scheduled')
            return response()->json(['message' => 'You can only edit a trip that has not yet started!'], 403);

        $validator = Validator::make($request->all(), [
            'route_id'   => ['integer', Rule::in($routes_id)],
            'trip_id'    => ['integer', Rule::in($trips_id)],
            'user_id'    => ['integer', Rule::in(\Auth::user()->agency->users()->where('type_id', 2)->pluck('id'))],
            'start_time' => 'string',
            'start_date' => 'date|date_format:Y-m-d',
            'end_time'   => 'string',
            'end_date'   => 'date|date_format:Y-m-d'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $trip->update($request->except('trip_status'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update trip. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The trip has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trip      = TripDescriptor::whereIn('route_id', $routes_id)->findOrFail($id);

        if($trip->trip_status != 'scheduled')
            return response()->json(['message' => 'You can only delete a trip that has not yet started!'], 403);

        try{
            $trip->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete trip. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Trip has been deleted.'], 200);
    }

    public function completedTrips(){
        $routes_id       = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $completed_trips = TripDescriptor::whereIn('route_id', $routes_id)->whereIn('trip_status', ['closed', 'canceled'])->get();
        return response()->json(compact('completed_trips'), 200);
    }


}
