@extends('layouts.layout')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col md:flex-row min-h-[75vh] md:h-[75vh] overflow-hidden">

            @include('chat.components.leftMenu')

            <div class="w-full md:w-2/3 min-w-0 min-h-[50vh] bg-white flex flex-col">

                <div class="p-4 border-b border-gray-200 flex justify-between items-center gap-3 bg-white shadow-sm z-10">
                    <h3 class="font-bold text-gray-900 text-lg min-w-0 truncate">
                        @if($chat->is_group)
                            {{ $chat->name }}
                        @else
                            {{$chat->users->where('id', '!=', auth()->id())->first()->username ?? 'System' }}
                        @endif
                    </h3>
                    <span class="text-sm text-gray-600 shrink-0 whitespace-nowrap">{{ $chat->users->count() }} members</span>
                </div>

                <div class="flex-1 overflow-y-auto p-4 space-y-4 bg-gray-50" id="chat-messages" data-chat-id="{{ $chat->id }}" data-is-group="{{ $chat->is_group ? 'true' : 'false' }}" role="log" aria-label="Messages" aria-live="polite">
                    @foreach($chat->messages as $message)
                        @if($message->user_id === auth()->id())
                            <div data-message-id="{{ $message->id }}" class="flex justify-end">
                                <div class="bg-teal-600 text-white px-4 py-2 rounded-2xl rounded-tr-sm max-w-[min(28rem,85%)] shadow-sm">
                                    <p class="text-sm whitespace-pre-wrap break-words">{{ $message->content }}</p>
                                    <time data-message-time datetime="{{ $message->created_at->toIso8601String() }}" class="text-xs text-teal-100 mt-1 block text-right">
                                    {{ $message->created_at->format('g:i A') }}
                                </time>
                                </div>
                            </div>
                        @else
                            <div data-message-id="{{ $message->id }}" class="flex justify-start">
                                <div class="flex items-end gap-2 max-w-[min(28rem,85%)] min-w-0">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($message->sender->username ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="bg-white border border-gray-200 text-gray-800 px-4 py-2 rounded-2xl rounded-tl-sm shadow-sm min-w-0">
                                        @if($chat->is_group)
                                            <span class="text-[11px] font-bold text-teal-600 block mb-1">{{ $message->sender->username }}</span>
                                        @endif
                                        <p class="text-sm whitespace-pre-wrap break-words">{{ $message->content }}</p>
                                        <time data-message-time datetime="{{ $message->created_at->toIso8601String() }}" class="text-xs text-gray-600 mt-1 block">
                                        {{ $message->created_at->format('g:i A') }}
                                    </time>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="p-4 bg-white border-t border-gray-200">
                    <form id="chat-form" action="{{ route('chat.store', $chat->id) }}" method="POST" class="flex gap-4">
                        @csrf
                        <label for="message-content" class="sr-only">Message</label>
                        <input id="message-content" type="text" name="content" maxlength="10000" value="{{ is_string(old('content')) ? old('content') : '' }}" placeholder="Type your message..." required autofocus autocomplete="off"
                               class="flex-1 min-w-0 appearance-none border border-gray-300 rounded-full px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 sm:text-sm">
                        <button id="submit-btn" type="submit" aria-label="Send message" class="cursor-pointer inline-flex items-center justify-center p-2 rounded-full bg-teal-600 text-white hover:bg-teal-700 transition duration-150 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            <svg class="w-5 h-5 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                    @error('content')
                        <p role="alert" class="mt-2 text-sm text-red-700">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>
    </div>

@endsection
