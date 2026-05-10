<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $chats = $user?->conversations()->with(['lastMessage', 'users'])->get();
        return view('chat.index', compact('chats'));
    }

    public function show($id)
    {
        $chat = Chat::with('messages.sender', 'users')->findOrFail($id);
        if(!$chat->users->contains(auth()->id())) {
            abort(403, 'Unauthorized access to chatroom');
        }
        $chats = auth()->user()->conversations()->with(['lastMessage', 'users'])->get();
        return view('chat.show', compact('chat','chats'));
    }

    public function store(Request $request, $chatId)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $chat = Chat::with('messages.sender')->findOrFail($chatId);
        if(!$chat->users->contains(auth()->id())) {
            abort(403, 'Unauthorized access to chatroom');
        }

        $chat->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return back();
    }
}
