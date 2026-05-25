<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        $achievements = Achievement::all();

        return response()->json($achievements);
    }

    public function userAchievements(Request $request)
    {
        try {
            $user = $request->user();

            $achievements = $user->achievements()
                ->get()
                ->map(function ($achievement) {
                    return [
                        'id' => $achievement->id,
                        'slug' => $achievement->slug,
                        'name' => $achievement->name,
                        'description' => $achievement->description,
                        'icon' => $achievement->icon,
                        'badge_color' => $achievement->badge_color,
                        'category' => $achievement->category,
                        'unlocked_at' => $achievement->pivot->unlocked_at,
                    ];
                });

            return response()->json($achievements);
        } catch (\Exception $e) {
            \Log::error('Achievements fetch error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load achievements', 'message' => $e->getMessage()], 500);
        }
    }

    public function unlock(Request $request, Achievement $achievement)
    {
        $user = $request->user();

        if ($user->achievements()->where('achievement_id', $achievement->id)->exists()) {
            return response()->json(['message' => 'Already unlocked'], 400);
        }

        $user->achievements()->attach($achievement->id, [
            'unlocked_at' => now(),
        ]);

        return response()->json(['message' => 'Achievement unlocked!']);
    }

    public function show(Achievement $achievement)
    {
        return response()->json($achievement);
    }
}
