<?php

namespace App\Http\Controllers;

use App\Models\VehicleDescriptor;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VehicleController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $vehicles = VehicleDescriptor::where('agency_id', \Auth::user()->agency_id)->get(['id', 'label', 'license_plate', 'created_at', 'updated_at']);
        return response()->json(compact('vehicles'), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'label'         => 'required|string|min:0',
            'license_plate' => 'required|string|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            VehicleDescriptor::create(array_merge($request->except('agency_id'),[
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create vehicle. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The vehicle has been created.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $vehicle = VehicleDescriptor::select(['id', 'label', 'license_plate', 'created_at', 'updated_at'])->where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('vehicle'), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $vehicle = VehicleDescriptor::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'label'         => 'string|min:0',
            'license_plate' => 'string|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $vehicle->update($request->except('agency_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update vehicle. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The vehicle has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $vehicle = VehicleDescriptor::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $vehicle->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete vehicle. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! The vehicle has been deleted.'], 200);
    }
}
