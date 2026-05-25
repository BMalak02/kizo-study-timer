<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable; //, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'level',
        'xp',
        'total_study_minutes',
        'completed_sessions',
        'current_streak',
        'last_session_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_session_date' => 'date',
        ];
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function challenges()
    {
        return $this->belongsToMany(Challenge::class, 'user_challenges')
                    ->withPivot('progress', 'completed', 'completed_at')
                    ->withTimestamps();
    }

    public function achievements()
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
                    ->withPivot('unlocked_at')
                    ->withTimestamps();
    }

    public function calculateLevel()
    {
        return intval($this->xp / 1000) + 1;
    }

    public function addXP($amount)
    {
        $this->xp += $amount;
        $this->level = $this->calculateLevel();
        $this->save();
    }
}
