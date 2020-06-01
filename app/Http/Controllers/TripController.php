<?php

namespace App\Http\Controllers;

use App\Models\Route;
use App\Models\Service;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TripController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $routes_id = Route::where('agency_id', \Auth::user()->agency_id)->pluck('id');
        $trips = Trip::with('route', 'service')->whereIn('route_id', $routes_id)
                    ->get(['id', 'route_id', 'service_id', 'headsign', 'short_name', 'direction_id',
		                    'block_id', 'wheelchair_accessible', 'bikes_allowed', 'created_at', 'updated_at']);
        return response()->json(compact('trips'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'route_id'              => ['required', 'integer', Rule::in(Route::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'service_id'            => ['required', 'integer', Rule::in(Service::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'headsign'              => 'string|min:0',
            'short_name'            => 'string|min:0',
            'direction_id'          => ['required', Rule::in(['going', 'return'])],
            'block_id'              => 'integer',
            'wheelchair_accessible' => [Rule::in(['empty', 'has', 'hasnt'])],
            'bikes_allowed'         => [Rule::in(['empty', 'has', 'hasnt'])],
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            Trip::create($request->all());
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

        $trip = Trip::with('route', 'service')->whereIn('route_id', $routes_id)
                    ->select(['id', 'route_id', 'service_id', 'headsign', 'short_name', 'direction_id',
                            'block_id', 'wheelchair_accessible', 'bikes_allowed', 'created_at', 'updated_at'])->findOrFail($id);
        return response()->json(compact('trip'), 200);
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

        $trip = Trip::whereIn('route_id', $routes_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'route_id'              => ['integer', Rule::in($routes_id)],
            'service_id'            => ['integer', Rule::in(Service::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'headsign'              => 'string|min:0',
            'short_name'            => 'string|min:0',
            'direction_id'          => [Rule::in(['going', 'return'])],
            'block_id'              => 'integer',
            'wheelchair_accessible' => [Rule::in(['empty', 'has', 'hasnt'])],
            'bikes_allowed'         => [Rule::in(['empty', 'has', 'hasnt'])],
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $trip->update($request->all());
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

        $trip = Trip::with('route', 'service')->whereIn('route_id', $routes_id)->findOrFail($id);

        try{
            $trip->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete trip. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Trip has been deleted.'], 200);
    }
}
