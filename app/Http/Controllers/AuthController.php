<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\User;
use App\Notifications\ForgetPassword;
use App\Notifications\WelcomeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;

class AuthController extends Controller {

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|string|email|max:255',
            'password' => 'required|string|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        try{
            if (!$token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]))
                return response()->json(['error' => 'Unauthorized'], 401);

        } catch (JWTException $e) {
            return response()->json(['error' => 'The token could not be created'], 500);
        }

        if(\Auth::check()){
            if(empty(\Auth::user()->email_verified_at))
                \Auth::user()->update(['email_verified_at' => Carbon::now()]);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser() {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate())
                return response()->json(['User not found'], 404);

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['Token expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['Token invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['Token absent'], $e->getStatusCode());
        }

        return response()->json(
            User::with(
                ['user_type' => function($q){
                        $q->select(['id', 'description']);
                    }
                , 'agency' => function($q){
                    $q->select(['id', 'name', 'timezone', 'lang', 'fare_url', 'phone', 'email', 'created_at', 'updated_at']);
                }])
                ->select(['id', 'agency_id', 'type_id', 'name', 'email'])
                ->findOrFail(\Auth::user()->id)
        );
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ], 200);
    }


    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|min:10|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'agency_name'     => 'required|string|max:255',
            'agency_url'      => 'required|string|max:255',
            'agency_timezone' => 'required|string|max:100',
            'agency_lang'     => 'string|max:10',
            'agency_phone'    => 'string|max:50',
            'agency_fare_url' => 'string|max:255',
            'agency_email'    => 'string|max:150'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        DB::beginTransaction();

        try {
            $agency = Agency::create([
                'name'     => $request->agency_name,
                'url'      => $request->agency_url,
                'timezone' => $request->agency_timezone,
                'lang'     => $request->agency_lang,
                'phone'    => $request->agency_phone,
                'fare_url' => $request->agency_fare_url,
                'email'    => $request->agency_email
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create Agency. Try again.'], 500);
        }

        try{
            $password = Str::random(10);

            $user = User::create([
                'agency_id' => $agency->id,
                'type_id'   => 1, // adm
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($password),
            ]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create User. Try again.'], 500);
        }

        try{
            $user->notify(new WelcomeNotification($password, $user));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not notify User. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! Check your inbox to find out your password.'],201);
    }

    public function forgetPassword(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        if(empty($user = User::where('email', $request->email)->first()))
            return response()->json(['error' => 'Could not find user'], 404);

        DB::beginTransaction();

        try{
            $password = Str::random(10);
            $user->update(['password' => Hash::make($password)]);

        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not create User. Try again.'], 500);
        }

        try{
            $user->notify(new ForgetPassword($password, $user));
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not notify User. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! Check your inbox to find out your password.'],200);
    }


    public function changePassword(Request $request){

        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'new_password' => 'required|min:10|max:255|confirmed'
        ]);

        if($validator->fails())
            return response()->json($validator->errors()->toJson(), 400);

        if(!Hash::check($request->password, \Auth::user()->password))
            return response()->json(['Incorrect credential.'], 400);

        DB::beginTransaction();

        try{
            \Auth::user()->update(['password' => Hash::make($request->new_password)]);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not notify User. Try again.'], 500);
        }

        DB::commit();

        return response()->json(['message' => 'Success! Your password has been changed.'],200);

    }

}
