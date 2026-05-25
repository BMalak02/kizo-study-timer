<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate(\App\Http\Requests\AppRequest::authRegister());

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Registered successfully', 'user' => $user], 201);
        }

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(\App\Http\Requests\AppRequest::authLogin());

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Logged in successfully', 'user' => Auth::user()]);
            }

            return redirect()->intended('dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
