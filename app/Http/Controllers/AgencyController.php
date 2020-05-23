<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgencyController extends Controller {

    public function index(){
        $agency = \Auth::user()->agency()->get(['id', 'name', 'timezone', 'lang', 'fare_url', 'phone', 'email', 'created_at', 'updated_at']);

        return response()->json(compact('agency'), 200);
    }

    public function update(Request $request){

        $agency = \Auth::user()->agency;

        $validator = Validator::make($request->all(), [
            'name'     => 'string|max:255',
            'url'      => 'string|max:255',
            'timezone' => 'string|max:100',
            'lang'     => 'string|max:10',
            'phone'    => 'string|max:50',
            'fare_url' => 'string|max:255',
            'email'    => 'string|max:150'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        try{
            $agency->update($request->input());

            return response()->json(['message' => 'Agency updated successfully'], 200);
        } catch (\Exception $e){
            return response()->json(['message' => 'The agency could not be updated. Try again'], 500);
        }
    }

}
