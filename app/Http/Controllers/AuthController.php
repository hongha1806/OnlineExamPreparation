<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admins', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        // admin
        $token = auth('admins')->attempt($credentials);
        if($token){
            return response()->json(["access_token"=>$token]);
        }
        // subject_head
        //  $token = auth('head_subjects')->attempt($credentials);
        // if($token){
        //     return response()->json(["access_token"=>$token]);
        // }
        // teachers
        $token = auth('teachers')->attempt($credentials);
        if($token){
            return response()->json(["access_token"=>$token]);
        }
        // students
        $token = auth('students')->attempt($credentials);
        if($token){
            return response()->json(["access_token"=>$token]);
        }
        //tim cac model va khong co ket qua thi se tra ve la khong tim
            return response()->json(['error' => 'Wrong email or password'], 400);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
