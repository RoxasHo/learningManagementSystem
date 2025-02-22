<!DOCTYPE html>
<html>
<head>
    <title>Account Rejection Notification</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>
    <p>Your account has been rejected for the following reason:</p>
    <p>{{ $rejectionReason }}</p>
    <p>If you have any questions, please contact us.</p>
    <p>Best regards,<br>Your LMS Team</p>
</body>
</html>
