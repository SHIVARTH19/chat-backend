<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Thread;
use App\Models\User;

class ChatController extends Controller
{
    public function getMessages(Request $request)
    {
        $request->validate(['thread_id' => 'required|exists:threads,id']);
        $messages = Message::where('thread_id', $request->thread_id)->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'thread_id' => 'required|exists:threads,id',
            'sender_id' => 'required|exists:users,id',
            'content' => 'required|string',
        ]);

        $message = Message::create($request->only('thread_id', 'sender_id', 'content'));
        
        return response()->json([
            'status' => 'success',
            'message_id' => $message->id
        ]);
    }
}
