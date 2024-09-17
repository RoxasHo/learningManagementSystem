<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderator Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('profile.updateModeratorPicture') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="moderatorPicture" class="profile-picture-label">
                    <img src="{{ $moderator->moderatorPicture ? asset('storage/' . $moderator->moderatorPicture) : asset('images/default-profile.png') }}" alt="Moderator Picture" class="profile-picture">
                    <input type="file" id="moderatorPicture" name="moderatorPicture" accept="image/*" class="profile-picture-input">
                    <span class="edit-icon">&#9998;</span>
                </label>
                <button type="submit" class="save-picture-button">Save Picture</button>
            </form>

            <div class="profile-info">
                <h2>{{ $moderator->name }}</h2>
                <p>Moderator ID: {{ $moderator->moderatorID }}</p>
                <p>Email: {{ $moderator->user->email }}</p>
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
