<!DOCTYPE html>
<!-- ModeratorRejectionEmail.php-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Application Rejected</title>
</head>
<body>
    <h2>Moderator Application Rejected</h2>
    <p>Dear {{ $moderator->name }},</p>
    <p>We regret to inform you that your request to become a moderator has been rejected.</p>

    <!-- Display the rejection reason -->
    <p><strong>Reason for Rejection:</strong></p>
    <p>{{ $rejectionReason }}</p>

    <p>If you have any questions, please contact support.012-301-9095</p>

    <p>Best regards,</p>
    <p>Your Application Team</p>
</body>
</html>
