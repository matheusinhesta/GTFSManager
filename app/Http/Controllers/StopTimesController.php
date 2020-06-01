<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Stop;
use App\Models\StopTime;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StopTimesController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $stops_id   = Stop::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $stop_times = StopTime::whereIn('stop_id', $stops_id)->get(['id', 'trip_id', 'stop_id', 'arrival_time', 'departure_time', 'stop_sequence', 'stop_headsign', 'pickup_type', 'drop_off_type', 'timepoint', 'created_at', 'updated_at']);
        return response()->json(compact('stop_times'), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trips_id = Trip::whereIn('route_id', $routes_id)->pluck('id');

        $validator = Validator::make($request->all(), [
            'trip_id'        => ['required', Rule::in($trips_id)],
            'stop_id'        => ['required', Rule::in(Stop::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'arrival_time'   => 'string|required_if:timepoint,1',
            'departure_time' => 'string|required_if:timepoint,1',
            'stop_sequence'  => 'required|integer|min:0',
            'stop_headsign'  => 'string|min:0',
            'pickup_type'    => ['string', Rule::in(['regular', 'hasnt', 'call', 'combine'])],
            'drop_off_type'  => ['string', Rule::in(['regular', 'hasnt', 'call', 'combine'])],
            'timepoint'      => ['boolean']
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            StopTime::create($request->all());
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create stop time. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The stop time has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $stops_id   = Stop::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $stop_time  = StopTime::select(['id', 'trip_id', 'stop_id', 'arrival_time', 'departure_time', 'stop_sequence', 'stop_headsign', 'pickup_type', 'drop_off_type', 'timepoint', 'created_at', 'updated_at'])
                                ->with('trip', 'stop')->whereIn('stop_id', $stops_id)->findOrFail($id);
        return response()->json(compact('stop_time'), 200);
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
        $trips_id = Trip::whereIn('route_id', $routes_id)->pluck('id');

        $stops_id   = Stop::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $stop_time = StopTime::whereIn('stop_id', $stops_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'trip_id'        => [Rule::in($trips_id)],
            'stop_id'        => [Rule::in(Stop::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'arrival_time'   => 'date_format:H:i:s|required_if:timepoint,1',
            'departure_time' => 'date_format:H:i:s|required_if:timepoint,1',
            'stop_sequence'  => 'integer|min:0',
            'stop_headsign'  => 'string|min:0',
            'pickup_type'    => ['string', Rule::in(['regular', 'hasnt', 'call', 'combine'])],
            'drop_off_type'  => ['string', Rule::in(['regular', 'hasnt', 'call', 'combine'])],
            'timepoint'      => ['boolean']
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $stop_time->update($request->all());
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update stop time. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The stop time has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $stops_id   = Stop::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $stop_time = StopTime::whereIn('stop_id', $stops_id)->findOrFail($id);

        try{
            $stop_time->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete stop time. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! The stop time has been deleted.'], 200);
    }
}
