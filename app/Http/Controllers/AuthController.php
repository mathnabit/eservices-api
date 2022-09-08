<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(AuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
    // Login
    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('api_token')->plainTextToken;
        return response()->json([
            'token' => $token
        ]);
    }
    // Get AuthenticatedUser 
    public function getUser(Request $request)
    {
        // Fetch the associated token Model
        // $token = \Laravel\Sanctum\PersonalAccessToken::findToken($request->token);
        // // Get the assigned user
        // $user = $token->tokenable;
        
        // return response()->json([
        //     'user' => $user
        // ]);
        return response()->json(auth()->user());
    }
    // Logout
    public function logout()
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'Logged out'
        ];
    }
}
