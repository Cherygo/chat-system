@extends('layouts.layout')

@section('content')
<div class="min-h-150 bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8 font-sans">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">

        <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            Log in to your account
        </h2>
        <p class="mt-2 text-center text-sm text-gray-600">
            Or
            <a href="{{ route('registration') }}" class="font-medium text-teal-600 hover:text-teal-500 transition ease-in-out duration-150">
                create a new account
            </a>
        </p>

    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-gray-100">

            <form class="space-y-6" action="{{ route('login.user') }}" method="POST">
                @csrf

                <div>

                    <label for="login" class="block text-sm font-medium text-gray-700">
                        Username or Email

                    </label>
                    <div class="mt-1">
                        <input id="login" name="login" required aria-invalid="{{ $errors->has('login') ? 'true' : 'false' }}" @error('login') aria-describedby="login-error" @enderror type="text" value="{{ is_string(old('login')) ? old('login') : '' }}" autofocus autocomplete="username" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition duration-150 ease-in-out @error('login') border-red-500 @enderror">

                    </div>
                    @error('login')
                    <p id="login-error" class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror

                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}" @error('password') aria-describedby="password-error" @enderror type="password" autocomplete="current-password" class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition duration-150 ease-in-out @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                    <p id="password-error" class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded transition duration-150 ease-in-out">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150 ease-in-out">
                        Log in
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
