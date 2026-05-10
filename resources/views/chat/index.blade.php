@include('components.header')


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex h-[75vh] overflow-hidden">

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

            <div class="w-2/3 bg-white flex flex-col items-center justify-center text-center px-8">
                <div class="w-20 h-20 bg-teal-50 rounded-full flex items-center justify-center mb-4 text-teal-500">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Welcome to Cherygo chat</h3>
                <p class="text-gray-500">Select a chat from the sidebar to start messaging, or create a new one.</p>
            </div>

        </div>
    </div>


@include('components.footer')
