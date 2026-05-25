<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $table = 'study_sessions';

    protected $fillable = [
        'user_id',
        'subject',
        'category',
        'duration_minutes',
        'xp_earned',
        'session_type',
        'status',
        'notes',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateXP()
    {
        $baseXP = $this->duration_minutes * 2;
        $multiplier = 1;

        if ($this->session_type === 'pomodoro') {
            $multiplier = 1.2;
        }

        return intval($baseXP * $multiplier);
    }
}
