<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessage;

class ChatController extends Controller
{
    // show chat page
    public function index($user_id)
    {
        $messages = ChatMessage::where(function ($q) use ($user_id) {
            $q->where('sender_id', 1)
              ->where('receiver_id', $user_id);
        })
        ->orWhere(function ($q) use ($user_id) {
            $q->where('sender_id', $user_id)
              ->where('receiver_id', 1);
        })
        ->orderBy('created_at')
        ->get();

        return view('chat_room', compact('messages', 'user_id'));
    }

    // send message
    public function send(Request $request)
    {
        ChatMessage::create([
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return back();
    }
}