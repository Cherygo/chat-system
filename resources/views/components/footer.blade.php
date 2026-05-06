<footer class="bg-white border-t border-gray-200 pt-12 pb-8 mt-auto">

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">

        <a href="{{route('index')}}" class="flex items-center gap-2">
            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
            </svg>
            <span class="text-xl font-bold text-gray-900 tracking-tight">Cherygo <span class="text-teal-600">chat</span></span>
        </a>

        <p class="text-sm text-gray-400">
            &copy; {{ date('Y') }} Cherygo chat. All rights reserved.
        </p>

        </div>
    </div>

</footer>
