@extends('layouts.layout')

@section('content')


    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col md:flex-row min-h-[75vh] md:h-[75vh] overflow-hidden">
            @include('chat.components.leftMenu')

            <div class="w-full md:w-2/3 min-w-0 min-h-64 bg-white flex flex-col items-center justify-center text-center px-8">
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


@endsection
