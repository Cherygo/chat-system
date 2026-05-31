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
            <button id="openGroupModal" class="mt-5 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded shadow-sm transition-colors cursor-pointer rounded-full">
                + New Group Chat
            </button>

            <div id="groupModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex items-center justify-center z-50">

                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">Create Group Chat</h2>

                    <form action="{{ route('chat.group')  }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Group Name</label>
                            <input type="text" name="name" required placeholder="e.g. The Rocket League Squad"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 px-3 py-2 border">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Invite Users</label>
                            <select name="user_ids[]" multiple required
                                    class="w-full border-gray-300 rounded-md shadow-sm h-32 focus:ring-teal-500 focus:border-teal-500 px-3 py-2 border">

                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->username }}
                                    </option>
                                @endforeach

                            </select>
                            <p class="text-[10px] text-gray-500 mt-1">Hold Ctrl (or Cmd) to select multiple</p>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" id="closeGroupModal" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md font-medium transition-colors cursor-pointer">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-md font-medium shadow-sm transition-colors cursor-pointer">Create</button>
                        </div>
                    </form>
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
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('groupModal');
        const openBtn = document.getElementById('openGroupModal');
        const closeBtn = document.getElementById('closeGroupModal');

        openBtn.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
</script>

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
