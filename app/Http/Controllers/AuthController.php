<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate request
        $request->validate([
            'nickname' => 'required|string',
        ]);

        // Check if the user already exists
        $user = User::where('nickname', $request->nickname)->first();

        if (!$user) {
            // Create a new user if not found
            $user = User::create([
                'nickname' => $request->nickname,
            ]);
        }

        // Generate a new token
        $token = $user->createToken('Personal Access Token')->plainTextToken;

        // Return the user data and token
        return response()->json([
            'id' => $user->id,
            'nickname' => $user->nickname,
            'token' => $token,
        ]);
    }
}
