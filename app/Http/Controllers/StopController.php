<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class StopController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $stops = Stop::where('agency_id', \Auth::user()->agency_id)->get(['id', 'code', 'name', 'desc', 'lat', 'lon', 'zone_id', 'url', 'location_type', 'parent_station', 'timezone', 'wheelchair_boarding', 'platform_code', 'created_at', 'updated_at']);
        return response()->json(compact('stops'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required|string|min:0',
            'desc'                  => 'required|string|min:0',
            'code'                  => 'string|min:0',
            'lat'                   => 'required_if:location_type,platform,station,entrance_exit|string|min:0',
            'lon'                   => 'required_if:location_type,platform,station,entrance_exit|string|min:0',
            'zone_id'               => ['required', 'integer', Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'stop_url'              => 'string|min:0',
            'location_type'         => ['required', Rule::in(['platform', 'station', 'entrance_exit', 'generic_node', 'boarding_area'])],
            'parent_station'        => 'integer|required_if:location_type,entrance_exit,generic_node,boarding_area',
            'wheelchair_boarding'   => [Rule::in(['empty', 'has', 'hasnt'])],
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            Stop::create(array_merge($request->except('agency_id'), [
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create stop. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The stop has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $stop = Stop::select(['id', 'code', 'name', 'desc', 'lat', 'lon', 'zone_id', 'url', 'location_type', 'parent_station', 'timezone', 'wheelchair_boarding', 'platform_code', 'created_at', 'updated_at'])
                    ->with('zone')->where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('stop'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $stop = Stop::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'                  => 'string|min:0',
            'desc'                  => 'string|min:0',
            'code'                  => 'string|min:0',
            'lat'                   => 'required_if:location_type,platform,station,entrance_exit|string|min:0',
            'lon'                  => 'required_if:location_type,platform,station,entrance_exit|string|min:0',
            'zone_id'               => ['integer', Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'stop_url'              => 'string|min:0',
            'location_type'         => [Rule::in(['platform', 'station', 'io', 'generic_node', 'boarding_area'])],
            'parent_station'        => 'integer|required_if:location_type,entrance_exit,generic_node,boarding_area',
            'wheelchair_boarding'   => [Rule::in(['has', 'hasnt'])]
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $stop->update($request->except('agency_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update stop. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The stop has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $stop = Stop::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $stop->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete stop. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! The stop has been deleted.'], 200);
    }
}
