<?php

namespace App\Http\Controllers;

use App\Models\FareAttribute;
use App\Models\FareRule;
use App\Models\Route;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FareRuleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($fare_id) {
        $fare  = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($fare_id);
        $rules = $fare->fare_rules;
        return response()->json(compact('rules'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($fare_id, Request $request) {
        $fare = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($fare_id);

        $validator = Validator::make($request->all(), [
            'route_id'       => ['required', Rule::in(Route::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'origin_id'      => [Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'destination_id' => [Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'contains_id'    => [Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))]
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            FareRule::create(array_merge($request->input(),[
                'fare_id' => $fare->id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create fare rule. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The fare rule has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($fare_id, $id) {
        $fare  = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($fare_id);
        $rule = $fare->fare_rules()->where('id', $id)->firstOrFail();
        return response()->json(compact('rule'), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($fare_id, Request $request, $id){
        $fare = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($fare_id);

        $validator = Validator::make($request->all(), [
            'route_id'       => [Rule::in(Route::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'origin_id'      => [Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'destination_id' => [Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))],
            'contains_id'    => [Rule::in(Zone::where('agency_id', \Auth::user()->agency_id)->pluck('id'))]
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        $rule = $fare->fare_rules()->where('id', $id)->firstOrFail();

        DB::beginTransaction();

        try{
            $rule->update($request->except('fare_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update fare rule. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The fare rule has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($fare_id, $id) {
        $fare = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($fare_id);
        $rule = $fare->fare_rules()->where('id', $id)->firstOrFail();

        try{
            $rule->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete fare rule. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! The fare rule has been deleted.'],200);
    }
}
