
<!--forget-password.blade.php-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset Request</title>
</head>
<body>
    <h2>Password Reset Request</h2>
    <p>Dear {{ $user->name }},</p>

    <p>You have requested to reset your password. To proceed with resetting your password, please click the link below:</p>
    
    <p>
        <a href="{{ $resetLink }}" style="padding: 10px; background-color: #4CAF50; color: white; text-decoration: none;">Reset Password</a>
    </p>
    
    <p><strong>Note:</strong> This link is valid for 2 minutes. If you do not use it within this time, it will expire, and you will need to request a new password reset.</p>

    <p>If you did not request a password reset, no further action is required. You can safely ignore this email.</p>
    
    <p>For security reasons, we recommend that you do not share this email or your password reset link with anyone.</p>

    <p>Thank you,</p>
    <p>From Learning Management System</p>
</body>
</html>
