<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profileModerator.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <form action="{{ route('profile.updateModeratorPicture') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="moderatorPicture" class="moderatorPicture-label">
                    <img src="{{ $moderator->moderatorPicture ? asset('storage/' . $moderator->moderatorPicture) : asset('images/default-profile.png') }}" alt="Moderator Picture" class="moderatorPicture">
                    <input type="file" id="moderatorPicture" name="moderatorPicture" accept="image/*" class="moderatorPicture-input">
                    <span class="edit-icon">&#9998;</span>
                </label>
                <button type="submit" class="save-picture-button">Save Picture</button>
            </form>
            <div class="moderator-info">
                <h2>{{ $moderator->name }}</h2>
                <p>Moderator ID: {{ $moderator->moderatorID }}</p>
                <p>Email: {{ $moderator->user->email }}</p>
                <p>Points: {{ $moderator->user->point }}</p>

                <button class="settings-button">Settings</button>
                <a href="{{ route('profile.editModerator', $moderator->user->email) }}" class="btn btn-primary">Edit Profile</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
        <div class="profile-content">
            <div class="profile-section">
                <h3>Blacklist User</h3>
                <p>{{ $moderator->blacklistUser ?? 'No users blacklisted.' }}</p>
            </div>
            <div class="profile-section">
                <h3>Reports Handled</h3>
                <p>{{ $moderator->reportsHandled ?? 'No reports handled yet.' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
