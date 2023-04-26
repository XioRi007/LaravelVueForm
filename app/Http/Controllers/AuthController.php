<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login the user.
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $token = Auth()->user()->createToken('token-name')->plainTextToken;
            return response()->json(['token' => $token, 'success'=>true]);
        }else{
            return response()->json(['success'=>false, 'error' => 'Invalid email or password.'], 401);
        }
    }

    /**
     * Logout.
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success'=>true]);
    }
}
