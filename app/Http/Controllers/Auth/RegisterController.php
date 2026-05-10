<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegisterPage() : View
    {
        return view('auth.registration');
    }

    public function registerUser(RegistrationRequest $request)
    {
        $validatedData = $request->validated();
        $user = User::query()->create($validatedData);

        $chat = Chat::create([
            'is_group' => false,
            'name' => 'Welcome Chat',
        ]);
        $chat->users()->attach($user);
        $chat->messages()->create([
            'user_id' => $user->id,
            'content' => "Welcome to the chat, $user->username!",
        ]);

        auth()->login($user);
        return redirect()->route('chat.index')->with('success', 'Successfully logged in');
    }
}
