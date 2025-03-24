<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        try {
            $token = JWTAuth::fromUser($user);
        } catch (Exception $e) {
            Log::error('Token generation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error generating token.'
            ], 500);
        }

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|string|min:6',
        ]);

        try {
            $credentials = $request->only('name', 'password');

            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        } catch (Exception $e) {
            Log::error('Token generation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error generating token.'
            ], 500);
        }

        return response()->json([
            'message' => 'User logged in successfully',
            'token' => $token
        ], 200);
    }

    public function me()
    {
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
        return response()->json([
            'user' => $user,
            'role' => $user->getRoleNames()->first()
        ], 200);
    }
}
