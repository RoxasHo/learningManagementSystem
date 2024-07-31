<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profileStudent.css') }}">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <form action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="profile-photo" class="profile-photo-label">
<img src="{{ $student->profile_photo_url ? asset('storage/' . $student->profile_photo_url) : asset('images/default-profile.png') }}" alt="Profile Photo" class="profile-photo">
                    <input type="file" id="profile-photo" name="profile_photo" accept="image/*" class="profile-photo-input">
                    <span class="edit-icon">&#9998;</span>
                </label>
                <button type="submit" class="save-photo-button">Save Photo</button>
            </form>
            <h2>{{ $student->name }}</h2>
            <p>Joined: {{ $student->created_at->format('F j, Y') }}</p>
            <button class="settings-button">Settings</button>
        </div>
        <div class="profile-content">
            <!-- Additional profile details can go here -->
        </div>
    </div>
</body>
</html>
