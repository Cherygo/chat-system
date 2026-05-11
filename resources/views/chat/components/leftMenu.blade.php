<div class="w-1/3 border-r border-gray-200 flex flex-col bg-gray-50">
    <div class="p-4 border-b border-gray-200 bg-white">
        <h2 class="text-lg font-bold text-gray-900 tracking-tight">Your Chats</h2>
    </div>

    <div class="flex-1 overflow-y-auto">
        @forelse($chats as $chat)
            <a href="{{ route('chat.show', $chat->id) }}" class="block p-4 border-b border-gray-100 hover:bg-gray-100 transition duration-150">
                <div class="flex justify-between items-center mb-1">
                            <span class="font-semibold text-gray-900">
                                @if($chat->is_group)
                                    {{ $chat->name }}
                                @else
                                    {{$chat->users->where('id', '!=', auth()->id())->first()->username ?? 'Unknown' }}
                                @endif
                            </span>
                    @if($chat->lastMessage)
                        <span class="text-xs text-gray-400">
                                    {{ $chat->lastMessage->created_at?->shortAbsoluteDiffForHumans() }}
                                </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 truncate">
                    {{ $chat->lastMessage ? $chat->lastMessage->content : 'No messages yet.' }}
                </p>
            </a>
        @empty
            <div class="p-8 text-center text-gray-500 text-sm">
                You have no active chats.
            </div>
        @endforelse
    </div>
</div>
