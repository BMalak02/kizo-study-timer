<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ChallengeInvite;
use App\Models\Challenge;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Mail\ChallengeInvitationMail;

class ChallengeInviteController extends Controller
{
    public function index(Request $request)
    {
        $invites = ChallengeInvite::where('invitee_email', $request->user()->email)
            ->where('status', 'pending')
            ->with(['inviter', 'challenge'])
            ->get();
            
        return response()->json($invites);
    }

    public function store(Request $request, $challengeId)
    {
        $request->validate(\App\Http\Requests\AppRequest::inviteStore());
        
        $challenge = Challenge::findOrFail($challengeId);

        $invite = ChallengeInvite::create([
            'inviter_id' => $request->user()->id,
            'invitee_email' => $request->email,
            'challenge_id' => $challenge->id,
            'status' => 'pending'
        ]);

        // Use Resend API via HTTP to avoid SMTP SSL issues on local Windows
        $html = view('emails.challenge-invite', ['invite' => $invite])->render();
        
        Http::withoutVerifying()
            ->withToken(env('MAIL_PASSWORD'))
            ->post('https://api.resend.com/emails', [
                'from' => 'onboarding@resend.dev',
                'to' => [$request->email],
                'subject' => 'You have been invited to a Study Challenge!',
                'html' => $html,
            ]);

        return response()->json(['message' => 'Invitation sent!', 'invite' => $invite], 201);
    }

    public function accept(Request $request, $id)
    {
        $invite = ChallengeInvite::findOrFail($id);

        if ($invite->invitee_email !== $request->user()->email) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $invite->status = 'accepted';
        $invite->save();

        // Add challenge to user
        $request->user()->challenges()->syncWithoutDetaching([$invite->challenge_id => ['progress' => 0]]);

        return response()->json(['message' => 'Challenge accepted!']);
    }

    public function decline(Request $request, $id)
    {
        $invite = ChallengeInvite::findOrFail($id);

        if ($invite->invitee_email !== $request->user()->email) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $invite->status = 'declined';
        $invite->save();

        return response()->json(['message' => 'Challenge declined.']);
    }
}
