<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $services = Service::where('agency_id', \Auth::user()->agency_id)->get(['id', 'name', 'created_at', 'updated_at']);
        return response()->json(compact('services'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            Service::create(array_merge($request->except('agency_id'),[
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create service. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The service has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $service = Service::select(['id', 'name', 'created_at', 'updated_at'])->with('trips', 'calendar_dates')->where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('service'), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $service = Service::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            $service->update($request->except('agency_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update service. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The service has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $service = Service::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $service->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete service. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Service has been deleted.'], 200);
    }
}
