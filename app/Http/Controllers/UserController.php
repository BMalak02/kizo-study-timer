<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $validated = $request->validate(\App\Http\Requests\AppRequest::userUpdate($request->user()->id));

        $user = $request->user();
        $user->update($validated);

        return response()->json($user);
    }

    public function profile(Request $request, $userId)
    {
        $user = \App\Models\User::findOrFail($userId);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'avatar' => $user->avatar,
            'level' => $user->level,
            'xp' => $user->xp,
            'total_study_minutes' => $user->total_study_minutes,
            'completed_sessions' => $user->completed_sessions,
            'current_streak' => $user->current_streak,
        ]);
    }

    public function leaderboard()
    {
        $leaderboard = \App\Models\User::orderBy('xp', 'desc')
            ->limit(100)
            ->get()
            ->map(function ($user, $index) {
                return [
                    'rank' => $index + 1,
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->avatar,
                    'level' => $user->level,
                    'xp' => $user->xp,
                    'total_study_minutes' => $user->total_study_minutes,
                    'current_streak' => $user->current_streak,
                ];
            });

        return response()->json($leaderboard);
    }
}
