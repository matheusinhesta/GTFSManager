<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\WelcomeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $users = User::with(['user_type' => function($q){
                            $q->select(['id', 'description']);
                        }])->where('agency_id', \Auth::user()->agency_id)
                           ->get(['id', 'type_id', 'name', 'email', 'created_at', 'updated_at']);


        return response()->json(['users' => $users], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|min:10|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'type_id'  => 'required|integer|exists:user_types,id',
            'password' => 'string|min:10|max:255|confirmed'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try {
            if(empty($request->password))
                $request->request->add(['password' => Str::random(10)]);

            $user = User::create(array_merge($request->all(), [
                'agency_id' => \Auth::user()->agency_id,
                'password'  => Hash::make($request->password)
            ]));

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create User. Try again.'], 500);
        }

        try {
            $user->notify(new WelcomeNotification($request->password, $user));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not notify User. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! The user was notified with his password at the registered email address.'],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $user = User::with(['user_type' => function($q){
                        $q->select(['id', 'description']);
                 }])->select(['id', 'type_id', 'name', 'email', 'created_at', 'updated_at'])
                    ->where('agency_id', \Auth::user()->agency_id)->findOrFail($id);
        return response()->json(compact('user'), 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $user = User::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'     => 'string|min:10|max:255',
            'email'    => 'string|email|max:255|unique:users,email,'.$id.',id,deleted_at,NULL',
            'type_id'  => 'integer|exists:user_types,id',
            'password' => 'string|min:10|max:255|confirmed'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try {
            $user->update(array_merge($request->all(), [
                'type_id'  => (\Auth::user()->id == $id ? \Auth::user()->type_id : $request->type_id),
                'password' => ($request->has('password') ? Hash::make($request->password) : $user->password)
            ]));

            if($request->has('password')){
                $user->notify(new WelcomeNotification($request->password, $user));

                DB::commit();

                return response()->json(['message' => 'Success! The user has been updated and was notified with his password at the registered email address.'],200);
            }

        } catch (\Exception $e){
            dd($e->getMessage());
            DB::rollback();
            return response()->json(['message' => 'Could not update User. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! User has been updated.'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if(\Auth::user()->id == $id)
            return response()->json('You cannot delete yourself.', 403);

        $user = User::where('agency_id', \Auth::user()->agency_id)->findOrFail($id);

        try{
            $user->delete();
        } catch (\Exception $e){
            return response()->json(['message' => 'Could not delete User. Try again.'], 500);
        }

        return response()->json(['message' => 'Success! User has been deleted.'], 200);
    }
}
