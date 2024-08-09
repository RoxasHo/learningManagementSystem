<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link rel="stylesheet" href="{{ asset('css/profileTeacher.css') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('profile.updateTeacherPicture') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="teacherPicture" class="teacherPicture-label">
                    <img src="{{ $teacher->teacherPicture ? asset('storage/' . $teacher->teacherPicture) : asset('images/default-profile.png') }}" alt="Teacher Picture" class="teacherPicture">
                    <input type="file" id="teacherPicture" name="teacherPicture" accept="image/*" class="teacherPicture-input">
                    <span class="edit-icon">&#9998;</span>
                </label>
                <button type="submit" class="save-picture-button">Save Picture</button>
            </form>

            <div class="teacher-info">
                <h2>{{ $teacher->user->name }}</h2>
                <p>Teacher ID: {{ $teacher->teacherID }}</p>
                <p>Email: {{ $teacher->user->email }}</p>
                <p>Points: {{ $teacher->user->point }}</p>

                <button class="settings-button">Settings</button>
                <a href="{{ route('profile.editTeacher', $teacher->user->email) }}" class="btn btn-primary">Edit Profile</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
        <div class="profile-content">
            <div class="profile-section">
                <h3>Certification</h3>
                @if ($teacher->certification)
                    <a href="{{ asset('storage/' . $teacher->certification) }}" target="_blank">Download Certification</a>
                @else
                    <p>No certification available.</p>
                @endif
            </div>
            <div class="profile-section">
                <h3>Identity Proof</h3>
                @if ($teacher->identityProof)
                    <a href="{{ asset('storage/' . $teacher->identityProof) }}" target="_blank">Download Identity Proof</a>
                @else
                    <p>No identity proof available.</p>
                @endif
            </div>
            <div class="profile-section">
                <h3>Courses Created</h3>
                <!-- Example content, replace with actual course data -->
                <p>No courses created yet.</p>
            </div>
        </div>
    </div>
</body>
</html>
