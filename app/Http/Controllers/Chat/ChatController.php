<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use App\Events\MessageEvent;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $chats = $user?->chats()->with(['lastMessage', 'users'])->get();
        return view('chat.index', compact('chats'));
    }

    public function show($id)
    {
        $chat = Chat::with('messages.sender', 'users')->findOrFail($id);
        if(!$chat->users->contains(auth()->id())) {
            abort(403, 'Unauthorized access to chatroom');
        }
        $chats = auth()->user()->chats()->with(['lastMessage', 'users'])->get();
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

        $message = $chat->messages()->create([
           'user_id' => auth()->id(),
           'content' => $validated['content'],
        ]);

        $message->load('sender');
        MessageEvent::dispatch($message);

        return back();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        if(!$query) {
            return response()->json([]);
        }

        $users = User::where('username', 'ILIKE', "%{$query}%" )
            ->where('id', '!=', auth()->id())
            ->limit(5)
            ->get(['id', 'username']);

        return response()->json($users);
    }

    public function startChat(User $user)
    {
        $chat = auth()->user()->chats()
            ->where('is_group', false)
            ->whereHas('users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->first();

        if(!$chat) {
            $chat = Chat::create([
               'is_group' => false,
               'name' => null,
            ]);

            $chat->users()->attach([auth()->id(), $user->id]);
        }

        return redirect()->route('chat.show', $chat->id);
    }
}
