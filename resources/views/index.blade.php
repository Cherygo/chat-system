<body class="bg-gray-50 font-sans antialiased text-gray-900 selection:bg-teal-500 selection:text-white flex flex-col min-h-screen">
@include('components.header')

<main class="flex-grow">

    <section class="relative bg-white overflow-hidden border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32">
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    <span class="block">Pointless conversations,</span>
                    <span class="block text-teal-600">anytime, anywhere.</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-2xl">
                    Welcome to Cherygo chat. The insanely simple, probably fast way to stay connected with the people(?) and communities that matter most to you.
                </p>

                <div class="mt-10 sm:flex sm:justify-center gap-4">
                    @guest
                        <div class="rounded-md shadow">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 transition duration-150 md:py-4 md:text-lg md:px-10">
                                Get Started for Free
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="#" class="w-full flex items-center justify-center px-8 py-3 border border-gray-300 text-base font-medium rounded-md text-teal-700 bg-white hover:bg-gray-50 transition duration-150 md:py-4 md:text-lg md:px-10">
                                Log In
                            </a>
                        </div>
                    @endguest

                    @auth
                        <div class="rounded-md shadow">
                            <a href="/messages" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700 transition duration-150 md:py-4 md:text-lg md:px-10 shadow-lg shadow-teal-500/30">
                                Open Your Chats
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-base text-teal-600 font-semibold tracking-wide uppercase">Features</h2>
                <p class="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl">
                    Everything you need to chat
                </p>
            </div>

            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition duration-200">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">It Tries To Be Fast</h3>
                    <p class="text-gray-500">Built on modern architecture ensuring your messages are delivered instantly, without delay(i think?).</p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition duration-200">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Not Private</h3>
                    <p class="text-gray-500">Your conversations are not your business. Cherygo chat keeps all your chats exposed for everyone to see and laugh.</p>
                </div>

                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100 hover:shadow-md transition duration-200">
                    <div class="w-12 h-12 bg-teal-100 text-teal-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Simple Interface</h3>
                    <p class="text-gray-500">A mostly clean, environment with some distractions designed so you can focus on the conversation.</p>
                </div>
            </div>
        </div>
    </section>
</main>

<footer class="bg-white border-t border-gray-200 mt-auto py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
        <div class="flex items-center gap-2 mb-4 md:mb-0">
            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            <span class="text-lg font-bold text-gray-900">Cherygo chat</span>
        </div>
        <p class="text-gray-400 text-sm">
            &copy; {{ date('Y') }} Cherygo chat. No rights reserved.
        </p>
    </div>
</footer>

</body>
</html>
