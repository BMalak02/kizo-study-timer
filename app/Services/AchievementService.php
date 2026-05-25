<?php

namespace App\Services;

use App\Models\User;
use App\Models\Achievement;

class AchievementService
{
    /**
     * Check all achievement conditions for a given user and unlock them if met.
     */
    public static function checkAchievements(User $user)
    {
        // Get slugs of achievements already unlocked by the user
        $unlocked = $user->achievements()->pluck('slug')->toArray();

        // 1. Sessions Achievements
        if (!in_array('first-session', $unlocked) && $user->completed_sessions >= 1) {
            self::unlock($user, 'first-session');
        }
        if (!in_array('10-sessions', $unlocked) && $user->completed_sessions >= 10) {
            self::unlock($user, '10-sessions');
        }
        if (!in_array('50-sessions', $unlocked) && $user->completed_sessions >= 50) {
            self::unlock($user, '50-sessions');
        }
        if (!in_array('100-sessions', $unlocked) && $user->completed_sessions >= 100) {
            self::unlock($user, '100-sessions');
        }

        // 2. XP Achievements
        if (!in_array('100-xp', $unlocked) && $user->xp >= 100) {
            self::unlock($user, '100-xp');
        }
        if (!in_array('500-xp', $unlocked) && $user->xp >= 500) {
            self::unlock($user, '500-xp');
        }
        if (!in_array('1000-xp', $unlocked) && $user->xp >= 1000) {
            self::unlock($user, '1000-xp');
        }

        // 3. Streak Achievements
        if (!in_array('3-day-streak', $unlocked) && $user->current_streak >= 3) {
            self::unlock($user, '3-day-streak');
        }
        if (!in_array('7-day-streak', $unlocked) && $user->current_streak >= 7) {
            self::unlock($user, '7-day-streak');
        }
        if (!in_array('30-day-streak', $unlocked) && $user->current_streak >= 30) {
            self::unlock($user, '30-day-streak');
        }

        // 4. Challenges Achievements
        $completedChallengesCount = $user->challenges()->wherePivot('completed', true)->count();
        
        if (!in_array('challenge-complete', $unlocked) && $completedChallengesCount >= 1) {
            self::unlock($user, 'challenge-complete');
        }
        if (!in_array('5-challenges', $unlocked) && $completedChallengesCount >= 5) {
            self::unlock($user, '5-challenges');
        }
    }

    /**
     * Unlock a specific achievement for a user.
     */
    protected static function unlock(User $user, string $slug)
    {
        $achievement = Achievement::where('slug', $slug)->first();
        if ($achievement) {
            // Attach achievement if it's not already attached to prevent duplicates (though we checked via slug)
            if (!$user->achievements()->where('achievement_id', $achievement->id)->exists()) {
                $user->achievements()->attach($achievement->id, ['unlocked_at' => now()]);
            }
        }
    }
}
