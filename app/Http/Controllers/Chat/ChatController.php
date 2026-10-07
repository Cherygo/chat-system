<?php

namespace App\Http\Controllers\Chat;

use App\Events\ChatEvent;
use App\Events\MessageEvent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $chats = $user?->chats()->with(['lastMessage', 'users'])->get();
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat.index', compact('chats', 'users'));
    }

    public function show($id)
    {
        $chat = Chat::with('messages.sender', 'users')->findOrFail($id);
        if (! $chat->users->contains(auth()->id())) {
            abort(403, 'Unauthorized access to chatroom');
        }
        $chats = auth()->user()->chats()->with(['lastMessage', 'users'])->get();
        $users = User::where('id', '!=', auth()->id())->get();

        return view('chat.show', compact('chat', 'chats', 'users'));
    }

    public function store(Request $request, $chatId)
    {
        $chat = Chat::with('users')->findOrFail($chatId);
        if (! $chat->users->contains(auth()->id())) {
            abort(403, 'Unauthorized access to chatroom');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:10000',
        ]);

        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        $message->load('sender');
        MessageEvent::dispatch($message);

        return redirect()->route('chat.show', $chat->id);
    }

    public function search(Request $request)
    {
        $validated = $request->validate(['query' => 'nullable|string|max:255']);
        $query = $validated['query'] ?? null;
        if (! $query) {
            return response()->json([]);
        }

        $users = User::whereLike('username', "%{$query}%")
            ->where('id', '!=', auth()->id())
            ->limit(5)
            ->get(['id', 'username']);

        return response()->json($users);
    }

    public function startChat(User $user)
    {
        $currentUserId = auth()->id();
        abort_if($user->id === $currentUserId, 422, 'Choose another user to start a chat.');

        [$chat, $created] = DB::transaction(function () use ($user, $currentUserId) {
            // Lock both participants in a stable order so simultaneous requests reuse one chat.
            User::whereKey([$currentUserId, $user->id])->orderBy('id')->lockForUpdate()->get();
            $chat = Chat::where('is_group', false)->has('users', '=', 2)
                ->whereHas('users', fn ($query) => $query->where('users.id', $currentUserId))
                ->whereHas('users', fn ($query) => $query->where('users.id', $user->id))
                ->first();

            if ($chat) {
                return [$chat, false];
            }

            $chat = Chat::create(['is_group' => false, 'name' => null]);
            $chat->users()->attach([$currentUserId, $user->id]);

            return [$chat, true];
        });

        if ($created) {
            ChatEvent::dispatch($chat, $currentUserId);
        }

        return redirect()->route('chat.show', $chat->id);
    }

    public function storeGroup(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => ['required', 'integer', 'distinct', 'exists:users,id', Rule::notIn([auth()->id()])],
        ]);
        $chat = DB::transaction(function () use ($validated) {
            $chat = Chat::create([
                'name' => $validated['name'],
                'is_group' => true,
            ]);
            $chat->users()->attach(array_merge($validated['user_ids'], [auth()->id()]));

            return $chat;
        });
        ChatEvent::dispatch($chat, auth()->id());

        return redirect()->route('chat.show', $chat->id);
    }
}
