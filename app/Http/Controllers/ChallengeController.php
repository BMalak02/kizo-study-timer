<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index()
    {
        $challenges = Challenge::where('is_active', true)
            ->orderBy('difficulty')
            ->get();

        return response()->json($challenges);
    }

    public function userChallenges(Request $request)
    {
        $user = $request->user();

        $challenges = $user->challenges()
            ->get()
            ->map(function ($challenge) {
                return [
                    'id' => $challenge->id,
                    'name' => $challenge->name,
                    'description' => $challenge->description,
                    'icon' => $challenge->icon,
                    'target_sessions' => $challenge->target_sessions,
                    'target_duration' => $challenge->target_duration,
                    'xp_reward' => $challenge->xp_reward,
                    'difficulty' => $challenge->difficulty,
                    'progress' => $challenge->pivot->progress,
                    'completed' => $challenge->pivot->completed,
                    'completed_at' => $challenge->pivot->completed_at,
                ];
            });

        return response()->json($challenges);
    }

    public function joinChallenge(Request $request, Challenge $challenge)
    {
        $user = $request->user();

        if ($user->challenges()->where('challenge_id', $challenge->id)->exists()) {
            return response()->json(['message' => 'Already joined this challenge'], 400);
        }

        $user->challenges()->attach($challenge->id, [
            'progress' => 0,
            'completed' => false,
        ]);

        return response()->json(['message' => 'Joined challenge successfully']);
    }

    public function updateProgress(Request $request, Challenge $challenge)
    {
        $user = $request->user();
        $validated = $request->validate(\App\Http\Requests\AppRequest::challengeUpdateProgress());

        $userChallenge = $user->challenges()
            ->where('challenge_id', $challenge->id)
            ->first();

        if (!$userChallenge) {
            return response()->json(['error' => 'Not enrolled in this challenge'], 404);
        }

        $completed = false;

        if ($challenge->target_sessions && $validated['progress'] >= $challenge->target_sessions) {
            $completed = true;
            $user->addXP($challenge->xp_reward);
        }

        $userChallenge->pivot->update([
            'progress' => $validated['progress'],
            'completed' => $completed,
            'completed_at' => $completed ? now() : null,
        ]);

        // Check for achievements
        \App\Services\AchievementService::checkAchievements($user);

        return response()->json([
            'progress' => $validated['progress'],
            'completed' => $completed,
        ]);
    }

    public function show(Challenge $challenge)
    {
        return response()->json($challenge);
    }
}
