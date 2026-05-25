<?php

namespace Database\Seeders;

use App\Models\Challenge;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $challenges = [
            [
                'name' => '5 Day Streak',
                'description' => 'Study for 5 consecutive days',
                'target_sessions' => 5,
                'target_duration' => null,
                'xp_reward' => 250,
                'difficulty' => 'easy',
                'is_active' => true,
            ],
            [
                'name' => '10 Hour Challenge',
                'description' => 'Complete 10 hours of study sessions',
                'target_sessions' => null,
                'target_duration' => 600,
                'xp_reward' => 500,
                'difficulty' => 'medium',
                'is_active' => true,
            ],
            [
                'name' => 'Focus Master',
                'description' => 'Complete 20 Pomodoro sessions',
                'target_sessions' => 20,
                'target_duration' => null,
                'xp_reward' => 750,
                'difficulty' => 'hard',
                'is_active' => true,
            ],
            [
                'name' => 'Early Bird',
                'description' => 'Study 3 sessions before 9 AM',
                'target_sessions' => 3,
                'target_duration' => null,
                'xp_reward' => 150,
                'difficulty' => 'easy',
                'is_active' => true,
            ],
            [
                'name' => 'Perfect Week',
                'description' => 'Study every day for a week',
                'target_sessions' => 7,
                'target_duration' => null,
                'xp_reward' => 600,
                'difficulty' => 'medium',
                'is_active' => true,
            ],
            [
                'name' => 'Subject Master',
                'description' => '15 sessions in a single subject',
                'target_sessions' => 15,
                'target_duration' => null,
                'xp_reward' => 500,
                'difficulty' => 'medium',
                'is_active' => true,
            ],
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
