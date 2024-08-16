<?php 

// app/Http/Controllers/UserController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = $request->userId;

        // Fetch all users except the current logged-in user
        $users = User::where('id', '!=', $currentUserId)->get();
        
        return response()->json($users);
    }
}
