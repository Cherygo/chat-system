@include('components.header')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex h-[75vh] overflow-hidden">

            <div class="w-1/3 border-r border-gray-200 flex flex-col bg-gray-50 hidden md:flex">
                <div class="p-4 border-b border-gray-200 bg-white">
                    <h2 class="text-lg font-bold text-gray-900 tracking-tight">Your Chats</h2>
                </div>
                <div class="flex-1 overflow-y-auto">
                    @foreach($chats as $chat)
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
                    @endforeach
                </div>
            </div>

            <div class="w-full md:w-2/3 bg-white flex flex-col">

                <div class="p-4 border-b border-gray-200 flex justify-between items-center bg-white shadow-sm z-10">
                    <h3 class="font-bold text-gray-900 text-lg">
                        {{ $chat->is_group ? $chat->name : '1-on-1 Chat' }}
                    </h3>
                    <span class="text-sm text-gray-500">{{ $chat->users->count() }} members</span>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="chat-messages">
                    @foreach($chat->messages as $message)
                        @if($message->sender_id === auth()->id())
                            <div class="flex justify-end">
                                <div class="bg-teal-600 text-white px-4 py-2 rounded-2xl rounded-tr-sm max-w-md shadow-sm">
                                    <p class="text-sm">{{ $message->content }}</p>
                                    <span class="text-[10px] text-teal-100 mt-1 block text-right">
                                    {{ $message->created_at->format('g:i A') }}
                                </span>
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start">
                                <div class="flex items-end gap-2 max-w-md">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ substr($message->sender->username ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-tl-sm shadow-sm">
                                        @if($chat->is_group)
                                            <span class="text-[11px] font-bold text-teal-600 block mb-1">{{ $message->sender->username }}</span>
                                        @endif
                                        <p class="text-sm">{{ $message->content }}</p>
                                        <span class="text-[10px] text-gray-400 mt-1 block">
                                        {{ $message->created_at->format('g:i A') }}
                                    </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="p-4 bg-white border-t border-gray-200">
                    <form id="chat-form" action="{{ route('chat.store', $chat->id) }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="text" name="content" placeholder="Type your message..." required autofocus autocomplete="off"
                               class="flex-1 appearance-none border border-gray-300 rounded-full px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        <button id="submit-btn" type="submit" class="cursor-pointer inline-flex items-center justify-center p-2 rounded-full bg-teal-600 text-white hover:bg-teal-700 transition duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            <svg class="w-5 h-5 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>


<script>
{{-- scroll to end of a chat--}}
        const messagesContainer = document.getElementById('chat-messages');
        if(messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }


{{--disable multiple message submissions in one page refresh--}}
    const messageContainer = document.getElementById('chat-messages');
    if(messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }

    const chatForm = document.getElementById('chat-form');
    const submitBtn = document.getElementById('submit-btn');

    if(chatForm) {
        chatForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        });
    }
</script>

@include('components.footer')
