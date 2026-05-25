<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // Sessions achievements
            [
                'slug' => 'first-session',
                'name' => 'Getting Started',
                'description' => 'Complete your first study session',
                'icon' => '🎬',
                'badge_color' => 'blue',
                'category' => 'sessions',
            ],
            [
                'slug' => '10-sessions',
                'name' => 'Momentum',
                'description' => 'Complete 10 study sessions',
                'icon' => '⚡',
                'badge_color' => 'blue',
                'category' => 'sessions',
            ],
            [
                'slug' => '50-sessions',
                'name' => 'Dedicated Learner',
                'description' => 'Complete 50 study sessions',
                'icon' => '🏆',
                'badge_color' => 'blue',
                'category' => 'sessions',
            ],
            [
                'slug' => '100-sessions',
                'name' => 'Study Master',
                'description' => 'Complete 100 study sessions',
                'icon' => '👑',
                'badge_color' => 'blue',
                'category' => 'sessions',
            ],

            // XP achievements
            [
                'slug' => '100-xp',
                'name' => 'First Steps',
                'description' => 'Earn 100 XP',
                'icon' => '⭐',
                'badge_color' => 'yellow',
                'category' => 'xp',
            ],
            [
                'slug' => '500-xp',
                'name' => 'Rising Star',
                'description' => 'Earn 500 XP',
                'icon' => '✨',
                'badge_color' => 'yellow',
                'category' => 'xp',
            ],
            [
                'slug' => '1000-xp',
                'name' => 'Level Up!',
                'description' => 'Earn 1000 XP and reach level 2',
                'icon' => '🚀',
                'badge_color' => 'yellow',
                'category' => 'xp',
            ],

            // Streak achievements
            [
                'slug' => '3-day-streak',
                'name' => 'On Fire',
                'description' => 'Maintain a 3 day study streak',
                'icon' => '🔥',
                'badge_color' => 'red',
                'category' => 'streak',
            ],
            [
                'slug' => '7-day-streak',
                'name' => 'Week Warrior',
                'description' => 'Maintain a 7 day study streak',
                'icon' => '💪',
                'badge_color' => 'red',
                'category' => 'streak',
            ],
            [
                'slug' => '30-day-streak',
                'name' => 'Unstoppable',
                'description' => 'Maintain a 30 day study streak',
                'icon' => '🌟',
                'badge_color' => 'red',
                'category' => 'streak',
            ],

            // Challenge achievements
            [
                'slug' => 'challenge-complete',
                'name' => 'Challenge Accepted',
                'description' => 'Complete your first challenge',
                'icon' => '🎯',
                'badge_color' => 'green',
                'category' => 'challenges',
            ],
            [
                'slug' => '5-challenges',
                'name' => 'Challenge Hunter',
                'description' => 'Complete 5 challenges',
                'icon' => '🏅',
                'badge_color' => 'green',
                'category' => 'challenges',
            ],

            // Special achievements
            [
                'slug' => 'early-adopter',
                'name' => 'Early Adopter',
                'description' => 'One of the first users',
                'icon' => '🎁',
                'badge_color' => 'purple',
                'category' => 'special',
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::create($achievement);
        }
    }
}
