<div class="w-full md:w-1/3 min-w-0 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col bg-gray-50">
    <div class="p-4 border-b border-gray-200 bg-white">
        <div class="p-4 border-b border-gray-200 bg-white">
            <h2 class="text-lg font-bold text-gray-900 tracking-tight mb-3">Your Chats</h2>

            <div class="relative relative-search-container">
                <label for="user-search" class="sr-only">Search for users</label>
                <input type="text" id="user-search" data-search-url="{{ route('chat.search') }}" data-start-url="{{ url('/chat/start') }}" placeholder="Search for users..." autocomplete="off"
                       class="w-full appearance-none border border-gray-300 rounded-full px-4 py-2 text-base sm:text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                <div id="search-results" aria-live="polite" class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden overflow-hidden">
                </div>
            </div>
            <button type="button" id="openGroupModal" @disabled($users->isEmpty()) class="mt-5 bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded shadow-sm transition-colors cursor-pointer rounded-full disabled:opacity-50 disabled:cursor-not-allowed">
                + New Group Chat
            </button>

            <dialog id="groupModal" aria-labelledby="group-title" data-has-errors="{{ $errors->has('name') || $errors->has('user_ids') || $errors->has('user_ids.*') ? 'true' : 'false' }}" class="m-auto p-0 border-0 rounded-xl w-[calc(100%-2rem)] max-w-md backdrop:bg-gray-900/50">

                <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6 transform transition-all">
                    <h2 id="group-title" class="text-xl font-bold mb-4 text-gray-800">Create Group Chat</h2>

                    @if($errors->has('name') || $errors->has('user_ids') || $errors->has('user_ids.*'))
                        <ul role="alert" class="mb-4 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <form action="{{ route('chat.group')  }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label for="group-name" class="block text-sm font-medium text-gray-700 mb-1">Group Name</label>
                            <input id="group-name" type="text" name="name" value="{{ is_string(old('name')) ? old('name') : '' }}" maxlength="255" required placeholder="e.g. The Rocket League Squad"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:ring-teal-500 focus:border-teal-500 px-3 py-2 border">
                        </div>

                        <div class="mb-6">
                            <label for="group-users" class="block text-sm font-medium text-gray-700 mb-1">Invite Users</label>
                            <select id="group-users" name="user_ids[]" multiple required
                                    class="w-full border-gray-300 rounded-md shadow-sm h-32 focus:ring-teal-500 focus:border-teal-500 px-3 py-2 border">

                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @selected(in_array($user->id, (array) old('user_ids', [])))>
                                        {{ $user->username }}
                                    </option>
                                @endforeach

                            </select>
                            <p class="text-sm text-gray-600 mt-1">Hold Ctrl (or Cmd) to select multiple</p>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" id="closeGroupModal" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-md font-medium transition-colors cursor-pointer">Cancel</button>
                            <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-md font-medium shadow-sm transition-colors cursor-pointer">Create</button>
                        </div>
                    </form>
                </div>
            </dialog>
        </div>
    </div>

    <div id="chat-list" class="flex-1 min-h-0 overflow-y-auto max-h-48 md:max-h-none">
        @forelse($chats as $chat)
            <a href="{{ route('chat.show', $chat->id) }}" data-chat-id="{{ $chat->id }}" class="block p-4 border-b border-gray-100 hover:bg-gray-100 transition duration-150">
                <div class="flex justify-between items-center gap-2 mb-1">
                            <span class="font-semibold text-gray-900 truncate">
                                @if($chat->is_group)
                                    {{ $chat->name }}
                                @else
                                    {{$chat->users->where('id', '!=', auth()->id())->first()->username ?? 'System' }}
                                @endif
                            </span>
                    @if($chat->lastMessage)
                        <span class="text-xs text-gray-600">
                                    {{ $chat->lastMessage->created_at?->shortAbsoluteDiffForHumans() }}
                                </span>
                    @endif
                </div>
                <p class="text-sm text-gray-500 truncate">
                    {{ $chat->lastMessage ? $chat->lastMessage->content : 'No messages yet.' }}
                </p>
            </a>
        @empty
            <div data-empty-chats class="p-8 text-center text-gray-500 text-sm">
                You have no active chats.
            </div>
        @endforelse
    </div>
</div>
