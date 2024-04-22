<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginPage(): string
    {
        return view('auth.login')->render();
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($validated, $request->has('remember'))) {
            throw ValidationException::withMessages(['username' => __('auth.failed')]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('log-viewer.index', absolute: false));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerate();

        return redirect()->intended(route('login', absolute: false));
    }
}
