<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChallengeInvite extends Model
{
    protected $fillable = [
        'inviter_id',
        'invitee_email',
        'challenge_id',
        'status',
    ];

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function challenge()
    {
        return $this->belongsTo(Challenge::class);
    }
}
