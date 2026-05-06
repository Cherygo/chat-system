@include('layouts.layout')
<div>
    <header class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="text-2xl font-bold text-teal-600 tracking-tight flex items-center gap-2">
                        Cherygo Chat
                    </a>
                </div>


                <div class="flex items-center space-x-2 sm:space-x-4">

                    @guest
                        <a href="{{route('auth.login')}}" class="text-sm font-medium text-gray-500 hover:text-teal-600 px-3 py-2 rounded-md transition duration-150 ease-in-out">
                            Log in
                        </a>
                        <a href="{{route('auth.registration')}}" class="text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 px-4 py-2 rounded-md shadow-sm transition duration-150 ease-in-out">
                            Sign up
                        </a>
                    @endguest

                    @auth
                        <a href="/messages" class="relative p-2 text-gray-400 hover:text-teal-600 hover:bg-gray-50 rounded-full transition duration-150 ease-in-out" aria-label="Messages">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path>
                            </svg>
                            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-teal-500 ring-2 ring-white"></span>
                        </a>

                        <button class="relative p-2 text-gray-400 hover:text-teal-600 hover:bg-gray-50 rounded-full transition duration-150 ease-in-out" aria-label="Notifications">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <span class="absolute top-1.5 right-1.5 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
                        </button>

                        <div class="flex items-center gap-4 ml-2 pl-4 border-l border-gray-200">
                            <button class="flex items-center focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-teal-100 border border-teal-200 text-teal-700 flex items-center justify-center text-sm font-bold">
                                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                                </div>
                            </button>

                            <form method="POST" action="{{ route('auth.logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm font-medium text-gray-500 hover:text-teal-600 transition duration-150 ease-in-out bg-transparent border-none p-0 cursor-pointer">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </header>
</div>
