<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 

class AuthController extends Controller
{
    //  User Register 
    public function register(Request $request)
    {
        // 1. Validation
        $request->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,provider', 
            'phone' => 'nullable|string|max:20',
        ]);

        // 2. User Create 
        $user = User::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
            'phone' => $request->phone,
        ]);

        // 3. Login Token Create 
        $token = $user->createToken('authToken')->plainTextToken;

        // 4. Response Return 
        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    // User Ko Login 
    public function login(Request $request)
    {
        // 1. Validation
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Credentials Check 
        $user = User::where('email', $request->email)->first();

        // 3. Password Verify
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // 4. Token Create (Login Successful)
        $token = $user->createToken('authToken')->plainTextToken;

        // 5. Response Return 
        return response()->json([
            'message' => 'Login successful.',
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }
    // LOGOUT METHOD 
    public function logout(Request $request)
    {
        // Deletes the specific token used for the current session from personal_access_tokens table
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully and token revoked.'
        ], 200);
    }
}