<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MockAuth
{
    public function handle(Request $request, Closure $next)
    {
        // Automatically login the first user for every request
        $user = User::first();
        if ($user) {
            Auth::login($user);
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        }
        return $next($request);
    }
}
