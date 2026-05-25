<!DOCTYPE html>
<html>
<head>
    <title>Invitation au défi</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">

    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eaeaea; border-radius: 8px;">
        <h2 style="color: #4a90e2;">🎯 Vous avez été invité !</h2>
        
        <p>Bonjour ! <strong>{{ $invite->inviter->name }}</strong> vous invite à rejoindre le défi <strong>{{ $invite->challenge->name }}</strong> sur Kizo Study Timer.</p>

        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 6px; margin: 20px 0;">
            <h3 style="margin-top: 0;">{{ $invite->challenge->icon }} {{ $invite->challenge->name }}</h3>
            <p style="margin-bottom: 0;"><em>{{ $invite->challenge->description }}</em></p>
        </div>

        <p>Acceptez ce défi pour suivre vos progrès d'étude, gagner de l'XP et concourir ensemble !</p>

        <a href="{{ env('FRONTEND_URL', 'http://localhost:3000') }}/challenges" 
           style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #28a745; border-radius: 5px; text-decoration: none; font-weight: bold;">
           Se connecter pour accepter
        </a>

        <p style="margin-top: 30px; font-size: 12px; color: #999;">
            Si vous n'avez pas de compte, vous pouvez en créer un en utilisant cet e-mail pour voir l'invitation.
        </p>
    </div>

</body>
</html>
