
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Registration Request</title>
</head>
<body>
    <h2>New Moderator Registration Request</h2>
    <p>Dear Superuser,</p>
    <p>A new moderator registration request has been submitted. Please review the details below:</p>

    <div class="profile-container">
        
        <!-- Display Moderator Picture using Base64 encoding -->
    
        <div class="profile-info">
            <ul>
                <li><strong>Name:</strong> {{ $moderator->name }}</li>
                <li><strong>Email:</strong> {{ $moderator->user->email }}</li>
                <li><strong>Gender:</strong> {{ $moderator->user->gender }}</li>
                <li><strong>Date of Birth:</strong> {{ $moderator->user->dateOfBirth }}</li>
                <li><strong>Contact Number:</strong> {{ $moderator->user->contactNumber }}</li>
                
                <!-- Download links for Certification and Identity Proof -->
                <li>
                    <strong>Certification:</strong> 
                    <a href="{{ url('storage/' . $moderator->certification) }}" class="btn btn-secondary btn-download" download>
                        Download Certification
                    </a>
                </li>
                <li>
                    <strong>Identity Proof:</strong> 
                    <a href="{{ url('storage/' . $moderator->identityProof) }}" class="btn btn-secondary btn-download" download>
                        Download Identity Proof
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <p>Actions:</p>
    <p>
        <a href="{{ url('approve-moderator/' . $moderator->approval_token) }}" style="padding: 10px; background-color: green; color: white; text-decoration: none;">Approve</a>
        <a href="{{ url('reject-moderator/' . $moderator->approval_token) }}" style="padding: 10px; background-color: red; color: white; text-decoration: none;">Reject</a>
    </p>

    <p>Thank you,</p>
    <p>Your Application Team</p>
</body>
</html>
