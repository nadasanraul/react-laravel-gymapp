<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use JWTAuth;
use App\User;

class AuthController extends Controller
{
    public function register(Request $request) {
        $this->validate(request(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->login($user);

        return $this->respondWithToken($token);
    }

    public function login(Request $request) {
        $data = $request->only(['email', 'password']);

        try  {
            if(!$token = auth()->attempt($data)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized'
                ], 401);
            }
        } catch(JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong'
            ], 500);
        }
        

        return $this->respondWithToken($token);
    }

    public function logout() {

        try {
            auth()->logout();
            return response()->json([
                'status' => 'success',
                'message' => 'user is logged out'
            ], 200);
        } catch(JWTException $e) {
            return  response()->json([
                'status' => 'error',
                'message' => 'user can not be logged out',
            ], 500);
        }

    }

    public function refresh() {

        try {
            $token = JWTAuth::refresh(JWTAuth::getToken());
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Could not create token'
            ], 500);
        }

        return $this->respondWithToken($token);
    }

    public function getUser() {
        try {
            $token = JWTAuth::getToken()->get();
        } catch (JWTException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 500);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email
            ]
        ]);
    }
}
