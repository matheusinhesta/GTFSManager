<?php

namespace App\Http\Controllers;

use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RouteController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $routes = Route::with(['attributions', 'entity_selectors', 'fare_rules', 'trips'])->where('agency_id', \Auth::user()->agency_id)->get();
        return response()->json(compact('routes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'short_name'  => 'required|string|max:255',
            'long_name'   => 'required|string|max:255',
            'desc'        => 'string|min:1|max:1000',
            'type'        => ['required', Rule::in(['vlt','subway','train','bus','ferry','tram','cable_car','cable_railway'])],
            'url'         => 'string|max:255',
            'color'       => 'string|max:255',
            'text_color'  => 'string|max:255',
            'sort_order' => 'integer|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try{
            Route::create(array_merge($request->except('agency_id'),[
                'agency_id' => \Auth::user()->agency_id
            ]));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create route. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The route has been registered.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $route = Route::with(['attributions', 'entity_selectors', 'fare_rules', 'trips'])
                        ->where('agency_id', \Auth::user()->agency_id)
                        ->findOrFail($id);
        return response()->json(compact('route'));
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
            'short_name'  => 'string|max:255',
            'long_name'   => 'string|max:255',
            'desc'        => 'string|min:1|max:1000',
            'type'        => [Rule::in(['vlt','subway','train','bus','ferry','tram','cable_car','cable_railway'])],
            'url'         => 'string|max:255',
            'color'       => 'string|max:255',
            'text_color'  => 'string|max:255',
            'sort_order' => 'integer|min:0'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        $route = Route::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $route->update(array_merge($request->except('agency_id')));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not update route. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The route has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $route = Route::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $route->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete route. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! Route has been deleted.'], 200);
    }
}
