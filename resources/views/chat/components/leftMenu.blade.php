<div class="w-1/3 border-r border-gray-200 flex flex-col bg-gray-50">
    <div class="p-4 border-b border-gray-200 bg-white">
        <div class="p-4 border-b border-gray-200 bg-white">
            <h2 class="text-lg font-bold text-gray-900 tracking-tight mb-3">Your Chats</h2>

            <div class="relative relative-search-container">
                <input type="text" id="user-search" placeholder="Search for users..." autocomplete="off"
                       class="w-full appearance-none border border-gray-300 rounded-full px-4 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">

                <div id="search-results" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden overflow-hidden">
                </div>
            </div>
        </div>
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


<script>
    const searchInput = document.getElementById('user-search');
    const resultsBox = document.getElementById('search-results');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            let query = this.value;

            if(query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            fetch(`/chat/api/search?query=${query}`)
                .then(response => response.json())
                .then(users => {
                    resultsBox.innerHTML = '';

                    if(users.length === 0) {
                        resultsBox.innerHTML = '<div class="p-3 text-sm text-gray-500 text-center">No users found.</div>';
                    } else {
                        users.forEach(user => {
                            resultsBox.innerHTML += `
                                <form action="/chat/start/${user.id}" method="POST" class="m-0 border-b border-gray-100 last:border-0">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700 transition duration-150 font-medium cursor-pointer">
                                        ${user.username}
                                    </button>
                                </form>
                            `;
                        });
                    }
                    resultsBox.classList.remove('hidden');
                });
        });

        // hide dropdown when clicking outside of it
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative-search-container')) {
                resultsBox.classList.add('hidden');
            }
        });
    }
</script>
