<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-id" content="{{ auth()->id() }}">
    @endauth
    <title>Cherygo Chat - Connect Instantly</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900 selection:bg-teal-500 selection:text-white flex flex-col min-h-screen">
    @include('components.header')
    @if(session('success'))
        <p role="status" class="max-w-7xl w-full mx-auto px-4 py-3 text-teal-800 bg-teal-50">{{ session('success') }}</p>
    @endif
    <main class="flex-grow">
        @yield('content')
    </main>
    @include('components.footer')
</body>
</html>
