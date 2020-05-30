<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ZoneController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $zones = Zone::where('agency_id', \Auth::user()->agency_id)->get(['id', 'name', 'created_at', 'updated_at']);
        return response()->json(compact('zones'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            Zone::create(array_merge($request->except('agency_id'),[
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create zone. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The zone has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $zone = Zone::select(['id', 'name', 'created_at', 'updated_at'])->where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('zone'), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        $zone = Zone::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $zone->update($request->except('agency_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create zone. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The zone has been registered.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $zone = Zone::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $zone->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete zone. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Zone has been deleted.'], 200);
    }
}
