<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class AuthController extends Controller
{

    use ApiResponser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh', 'logout']]);        
    }

    /**
     * Get a JWT via given credentials.
     * 
     * @param  Request  $request
     * @return Response
     * 
     */

    public function login(Request $request)
    {

        $rules = [
            'email'     => 'required|string',
            'password'  => 'required|string'
        ];

        $this->validate($request, $rules);

        $credentials = $request->only(['email', 'password']);

        $user = Auth::attempt($credentials);

        if(!$user){
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($user);

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
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(Auth::refresh());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

}
