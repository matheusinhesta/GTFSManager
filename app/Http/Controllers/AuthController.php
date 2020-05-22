<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\User;
use App\Notifications\WelcomeNotification;
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
        try{
            if (!$token = JWTAuth::attempt(['email' => $request->email, 'password' => $request->password]))
                return response()->json(['error' => 'Unauthorized'], 401);

        } catch (JWTException $e) {
            return response()->json(['error' => 'The token could not be created'], 500);
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
            if (! $user = JWTAuth::parseToken()->authenticate())
                return response()->json(['user_not_found'], 404);

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
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
        ]);
    }


    public function register(Request $request) {

        $validator = Validator::make($request->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|string|email|max:255|unique:users',
            'agency_name'     => 'required|string|max:255',
            'agency_url'      => 'required|string|max:255',
            'agency_timezone' => 'required|string|max:100',
            'agency_lang'     => 'string|max:10',
            'agency_phone'    => 'string|max:30',
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
            return response()->json(['message' => 'Could not create User. Try again.'], 500);
        }

        try{
            $user->notify(new WelcomeNotification($password, $user));
            DB::commit();

            return response()->json(['message' => 'Success! Check your inbox to find out your password.'],201);
        } catch (\Exception $e){
            DB::rollback();
            return response()->json(['message' => 'Could not notify User. Try again.'], 500);
        }
    }

}
