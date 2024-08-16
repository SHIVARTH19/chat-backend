<?php

// app/Http/Controllers/ThreadController.php

namespace App\Http\Controllers;

use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function index(Request $request)
    {
        // Get the current authenticated user ID
        $userId = $request->userId;

        // Fetch threads where the user is either the sender or receiver
        $threads = Thread::where(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })->with(['sender', 'receiver'])->get();

        return response()->json($threads);
    }

    public function store(Request $request)
    {
        $userId = $request->input('sender_id');
    
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);
    
        $receiverId = $request->input('receiver_id');
    
        // Check if a thread already exists between the current user and the receiver
        $existingThread = Thread::where(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $userId)
                  ->where('receiver_id', $receiverId);
        })->orWhere(function ($query) use ($userId, $receiverId) {
            $query->where('sender_id', $receiverId)
                  ->where('receiver_id', $userId);
        })->with(['sender', 'receiver'])->first();
    
        if ($existingThread) {
            // Return the existing thread with sender and receiver details
            return response()->json([
                'existing' => true,
                'thread' => $existingThread,
            ], 200);
        }
    
        // Create a new thread if none exists
        $thread = Thread::create([
            'sender_id' => $userId,
            'receiver_id' => $receiverId,
        ]);
    
        $thread->load(['sender', 'receiver']); // Eager load sender and receiver
    
        // Return the newly created thread with sender and receiver details
        return response()->json([
            'existing' => false,
            'thread' => $thread,
        ], 201);
    }
    
    

}
