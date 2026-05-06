<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegistrationRequest;
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
        auth()->login($user);

        return redirect('/')->with('success', 'Successfully logged in');
    }
}
