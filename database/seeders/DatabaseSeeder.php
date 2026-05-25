<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ChallengeSeeder::class,
            SocialChallengesSeeder::class,
            AchievementSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@kizo.com',
            'password' => bcrypt('password123'),
            'level' => 12,
            'xp' => 1200,
        ]);
    }
}
