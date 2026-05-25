<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Challenge;

class SocialChallengesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $challenges = [
            [
                'name' => 'Marathon du Weekend 🏃‍♂️',
                'description' => 'Étudiez 10 sessions pendant ce weekend pour prouver votre détermination.',
                'target_sessions' => 10,
                'difficulty' => 'hard',
                'target_duration' => null,
                'xp_reward' => 500,
            ],
            [
                'name' => 'L\'Oiseau de Nuit 🦉',
                'description' => 'Faites au moins 3 sessions tard le soir. Parfait pour les révisions de dernière minute !',
                'target_sessions' => 3,
                'difficulty' => 'medium',
                'target_duration' => null,
                'xp_reward' => 300,
            ],
            [
                'name' => 'Sprint Mathématique 🧮',
                'description' => 'Terminez 5 sessions intensives dans la catégorie Mathématiques.',
                'target_sessions' => 5,
                'difficulty' => 'easy',
                'target_duration' => 125,
                'xp_reward' => 200,
            ],
            [
                'name' => 'Focus Absolu 🧘',
                'description' => 'Survivez à 20 sessions totales cette semaine. Éliminez toutes distractions.',
                'target_sessions' => 20,
                'difficulty' => 'hard',
                'target_duration' => 500,
                'xp_reward' => 1000,
            ],
            [
                'name' => 'Le Départ Parfait 🌅',
                'description' => 'Commencez la journée avec 2 sessions étudiées avant midi.',
                'target_sessions' => 2,
                'difficulty' => 'easy',
                'target_duration' => 50,
                'xp_reward' => 150,
            ],
            [
                'name' => 'Polyglotte en devenir 🌍',
                'description' => 'Accumulez 8 sessions dédiées à l\'apprentissage des langues.',
                'target_sessions' => 8,
                'difficulty' => 'medium',
                'target_duration' => 200,
                'xp_reward' => 400,
            ],
            [
                'name' => 'Détente Productive ☕',
                'description' => 'Faites 12 petites sessions de 10 minutes à travers la semaine pour prouver la valeur de la régularité.',
                'target_sessions' => 12,
                'difficulty' => 'medium',
                'target_duration' => 120,
                'xp_reward' => 350,
            ],
            [
                'name' => 'Premier 30 Heures 🏆',
                'description' => 'Le but ultime : Étudiez pendant 30 heures totales (l\'équivalent de 72 sessions Pomodoro !)',
                'target_sessions' => 72,
                'difficulty' => 'hard',
                'target_duration' => 1800,
                'xp_reward' => 5000,
            ]
        ];

        foreach ($challenges as $challenge) {
            Challenge::create($challenge);
        }
    }
}
