<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginPage(Request $request)
    {
        return view('auth.login');
    }

    public function loginUser(LoginRequest $request)
    {
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (auth()->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('chat.index'))->with('success', 'Successfully logged in');
        }
        throw ValidationException::withMessages([
            'login' => 'Provided credentials do not match our records',
        ]);
    }
}
