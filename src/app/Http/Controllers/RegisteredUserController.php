<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    public function create(): \Illuminate\View\View
    {
        return view('auth.create');
    }

    public function store(RegisterRequest $request): \Illuminate\Http\RedirectResponse
    {
        $request->validated();
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->route("home")->with('success','Your account has been created successfully');
    }
}
