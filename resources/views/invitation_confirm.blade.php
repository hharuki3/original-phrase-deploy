<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invitation Email</title>
</head>
<body>
    <p>Dear {{ $recipientName }},</p>

    <p>You have been invited to join a group. Click the link below to join:</p>

    <p><a href="{{ $url }}">{{ $url }}</a></p>
    <p>Best regards,</p>
    <p>{{ $fromName }}</p>
</body>
</html>
