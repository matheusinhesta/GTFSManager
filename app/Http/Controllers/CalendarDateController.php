<?php

namespace App\Http\Controllers;

use App\Models\CalendarDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalendarDateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $dates = CalendarDate::where('agency_id', \Auth::user()->agency_id)->get();
        return response()->json(compact('dates'), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'date'           => 'required|date|date_format:Y-m-d',
            'exception_type' => ['required', Rule::in(['available', 'not_available'])]
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            CalendarDate::create(array_merge($request->except('agency_id'),[
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create calendar date. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The calendar date has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $date = CalendarDate::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('date'), 200);
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
            'date'           => 'date|date_format:Y-m-d',
            'exception_type' => [Rule::in(['available', 'not_available'])]
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        $date = CalendarDate::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $date->update($request->except('agency_id'));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update calendar date. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The calendar date has been updated.'],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $date = CalendarDate::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $date->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete calendar date. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Calendar date has been deleted.'], 200);
    }
}
