<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $sessions = $user->sessions()
            ->orderBy('started_at', 'desc')
            ->paginate(20);

        return response()->json($sessions);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(\App\Http\Requests\AppRequest::sessionStore());

        $user = $request->user();

        $session = Session::create([
            'user_id' => $user->id,
            ...$validated,
            'started_at' => now(),
            'status' => 'completed',
        ]);

        // Calculate XP
        $xpEarned = $session->calculateXP();
        $session->xp_earned = $xpEarned;
        $session->completed_at = now();
        $session->save();

        // Update user stats
        $user->total_study_minutes += $session->duration_minutes;
        $user->completed_sessions += 1;
        $user->addXP($xpEarned);

        // Update streak
        $today = Carbon::now()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();

        if ($user->last_session_date == $yesterday) {
            $user->current_streak += 1;
        } elseif ($user->last_session_date != $today) {
            $user->current_streak = 1;
        }

        $user->last_session_date = $today;
        $user->save();

        // Update Challenge Progress
        $activeChallenges = $user->challenges()->wherePivot('completed', false)->get();
        foreach ($activeChallenges as $challenge) {
            $newProgress = $challenge->pivot->progress + 1;
            $completed = false;
            
            if ($challenge->target_sessions && $newProgress >= $challenge->target_sessions) {
                $completed = true;
            }

            $user->challenges()->updateExistingPivot($challenge->id, [
                'progress' => $newProgress,
                'completed' => $completed,
                'completed_at' => $completed ? now() : null,
            ]);

            if ($completed) {
                $user->addXP($challenge->xp_reward);
            }
        }

        // Check for achievements
        \App\Services\AchievementService::checkAchievements($user);

        return response()->json($session, 201);
    }

    public function show(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        if ($session->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($session);
    }

    public function destroy(Request $request, $id)
    {
        $session = Session::findOrFail($id);

        if ($session->user_id !== $request->user()->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $session->delete();

        return response()->json(['message' => 'Session deleted']);
    }

    public function stats(Request $request)
    {
        $user = $request->user();
        $days = $request->query('days', '7');
        $startDate = now()->subDays($days - 1)->startOfDay();

        // Get daily minutes for the chart
        $daily = $user->sessions()
            ->where('completed_at', '>=', $startDate)
            ->selectRaw('DATE(completed_at) as date, SUM(duration_minutes) as minutes')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('minutes', 'date')
            ->toArray();

        // Fill in missing days with 0
        $chartData = [];
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($days - 1 - $i)->toDateString();
            $label = now()->subDays($days - 1 - $i)->format('D');
            $chartData[] = [
                'label' => $label,
                'minutes' => $daily[$date] ?? 0
            ];
        }

        $stats = [
            'total_minutes' => $user->total_study_minutes,
            'completed_sessions' => $user->completed_sessions,
            'current_streak' => $user->current_streak,
            'level' => $user->level,
            'xp' => $user->xp,
            'minutes_this_period' => $user->sessions()
                ->where('completed_at', '>=', $startDate)
                ->sum('duration_minutes'),
            'sessions_this_period' => $user->sessions()
                ->where('completed_at', '>=', $startDate)
                ->count(),
            'chart_data' => $chartData,
            'active_challenges' => $user->challenges()
                ->wherePivot('completed', false)
                ->get()
                ->map(function ($challenge) {
                    return [
                        'id' => $challenge->id,
                        'name' => $challenge->name,
                        'description' => $challenge->description,
                        'icon' => $challenge->icon,
                        'progress' => $challenge->pivot->progress,
                        'target_sessions' => $challenge->target_sessions,
                        'xp_reward' => $challenge->xp_reward,
                        'completed' => $challenge->pivot->completed,
                    ];
                }),
        ];

        return response()->json($stats);
    }
}
