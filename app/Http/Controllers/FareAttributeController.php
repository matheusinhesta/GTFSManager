<?php

namespace App\Http\Controllers;

use App\Models\FareAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class FareAttributeController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $fares = FareAttribute::where('agency_id', \Auth::user()->agency_id)->get(['id', 'price', 'currency_type', 'payment_method', 'transfers', 'transfer_duration', 'created_at', 'updated_at']);
        return response()->json(compact('fares'), 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'price'             => 'required|numeric|min:0',
            'currency_type'     => 'required|string|max:255',
            'payment_method'    => 'required|boolean',
            'transfers'         => ['nullable', Rule::in(['unallowed', 'one', 'two'])],
            'transfer_duration' => 'integer|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            FareAttribute::create(array_merge($request->except('agency_id'),[
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create fare. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The fare has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $fare = FareAttribute::select(['id', 'price', 'currency_type', 'payment_method', 'transfers', 'transfer_duration', 'created_at', 'updated_at'])->with(['fare_rules'])->where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('fare'), 200);
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
            'price'             => 'numeric|min:0',
            'currency_type'     => 'string|max:255',
            'payment_method'    => 'boolean',
            'transfers'         => ['nullable', Rule::in(['unallowed', 'one', 'two'])],
            'transfer_duration' => 'integer|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        $fare = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $fare->update($request->except('agency_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update fare. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The fare has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $fare = FareAttribute::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $fare->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete fare. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Fare has been deleted.'], 200);
    }
}
