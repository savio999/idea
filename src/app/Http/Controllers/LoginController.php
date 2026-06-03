<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(): \Illuminate\View\View
    {
        return view('auth.login');
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route("home")->with("success","Successfully logged out");
    }

    public function store(LoginRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();

        if (!Auth::attempt($request->only('email','password'))) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }
        
        $request->session()->regenerate();
        return redirect()->route("home")->with("success","Login successful");
    }
}
